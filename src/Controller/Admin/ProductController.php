<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Controller\Admin;

use Module\Shop\Form\AdminProductExportFilter;
use Module\Shop\Form\AdminProductExportForm;
use Module\Shop\Form\AdminProductSearchFilter;
use Module\Shop\Form\AdminProductSearchForm;
use Module\Shop\Form\ProductAdditionalFilter;
use Module\Shop\Form\ProductAdditionalForm;
use Module\Shop\Form\ProductFilter;
use Module\Shop\Form\ProductForm;
use Module\Shop\Form\ProductPriceFilter;
use Module\Shop\Form\ProductPriceForm;
use Module\Shop\Form\RelatedFilter;
use Module\Shop\Form\RelatedForm;
use Pi;
use Pi\File\Transfer\Upload;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Laminas\Db\Sql\Predicate\Expression;

class ProductController extends ActionController
{
    protected $ImageProductPrefix = 'product-';

    public function indexAction()
    {
        // Get page
        $module      = $this->params('module');
        $page        = $this->params('page', 1);
        $status      = $this->params('status');
        $category    = $this->params('category');
        $brand       = $this->params('brand');
        $recommended = $this->params('recommended');
        $title       = $this->params('title');
        $code        = $this->params('code');
        $sort        = $this->params('sort', 'create');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set sort
        switch ($sort) {
            case 'title':
                $order = ['title DESC', 'id DESC'];
                break;

            case 'titleASC':
                $order = ['title ASC', 'id ASC'];
                break;

            case 'hits':
                $order = ['hits DESC', 'id DESC'];
                break;

            case 'hitsASC':
                $order = ['hits ASC', 'id ASC'];
                break;

            case 'create':
                $order = ['time_create DESC', 'id DESC'];
                break;

            case 'createASC':
                $order = ['time_create ASC', 'id ASC'];
                break;

            case 'update':
                $order = ['time_update DESC', 'id DESC'];
                break;

            case 'updateASC':
                $order = ['time_update ASC', 'id ASC'];
                break;

            case 'recommended':
                $order = ['recommended DESC', 'time_create DESC', 'id DESC'];
                break;

            case 'price':
                $order = ['price DESC', 'id DESC'];
                break;

            case 'priceASC':
                $order = ['price ASC', 'id ASC'];
                break;

            case 'stock':
                $order = ['stock DESC', 'id DESC'];
                break;

            case 'stockASC':
                $order = ['stock ASC', 'id ASC'];
                break;

            case 'sold':
                $order = ['sold DESC', 'id DESC'];
                break;

            default:
                $order = ['time_create DESC', 'id DESC'];
                break;
        }

        // Set info
        $offset  = (int)($page - 1) * $this->config('admin_perpage');
        $limit   = intval($this->config('admin_perpage'));
        $product = [];

        // Set where
        $whereProduct = [];
        if (!empty($recommended)) {
            $whereProduct['recommended'] = 1;
        }
        if (!empty($brand)) {
            $whereProduct['brand'] = $brand;
        }
        if (!empty($category)) {
            $productId  = [];
            $whereLink  = ['category' => $category];
            $selectLink = $this->getModel('link')->select()->where($whereLink);
            $rowLink    = $this->getModel('link')->selectWith($selectLink);
            foreach ($rowLink as $link) {
                $productId[] = $link['product'];
            }
            if (!empty($productId)) {
                $whereProduct['id'] = $productId;
            } else {
                $whereProduct['id'] = 0;
            }
        }
        if (!empty($status) && in_array($status, [1, 2, 3, 4, 5])) {
            $whereProduct['status'] = $status;
        } else {
            $whereProduct['status'] = [1, 2, 3, 4];
        }
        if (!empty($code)) {
            $whereProduct['code LIKE ?'] = '%' . $code . '%';
        }
        if (!empty($title)) {
            // Set title
            if (Pi::service('module')->isActive('search') && isset($title) && !empty($title)) {
                $title = Pi::api('api', 'search')->parseQuery($title);
            } elseif (isset($title) && !empty($title)) {
                $title = _strip($title);
            }
            $title = is_array($title) ? $title : [$title];

            // Set where
            $titleWhere = function ($where) use ($title) {
                // title
                $whereTitleKey = clone $where;
                foreach ($title as $term) {
                    $whereTitleKey->like('title', '%' . $term . '%')->and;
                }
                // subtitle
                $whereSubTitleKey = clone $where;
                foreach ($title as $term) {
                    $whereSubTitleKey->like('subtitle', '%' . $term . '%')->and;
                }
                // Set where
                $where->andPredicate($whereTitleKey)->orPredicate($whereSubTitleKey);
            };
        }

        // Get list of product
        $select = $this->getModel('product')->select();
        if (!empty($title)) {
            $select->where($titleWhere);
        }
        $select->where($whereProduct)->order($order)->offset($offset)->limit($limit);
        $rowSet = $this->getModel('product')->selectWith($select);

        // Make list
        foreach ($rowSet as $row) {
            $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row);
        }

        // Set count
        $columnsLink = ['count' => new Expression('count(*)')];
        $select      = $this->getModel('product')->select();
        if (!empty($title)) {
            $select->where($titleWhere);
        }
        $select->where($whereProduct)->columns($columnsLink);
        $count = $this->getModel('product')->selectWith($select)->current()->count;

        // Set title
        $title = is_array($title) ? implode(' ', $title) : $title;

        // Set paginator
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(
            [
                'router' => $this->getEvent()->getRouter(),
                'route'  => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
                'params' => array_filter(
                    [
                        'module'      => $this->getModule(),
                        'controller'  => 'product',
                        'action'      => 'index',
                        'category'    => $category,
                        'brand'       => $brand,
                        'status'      => $status,
                        'title'       => $title,
                        'code'        => $code,
                        'recommended' => $recommended,
                        'sort'        => $sort,
                    ]
                ),
            ]
        );

        // Set form
        $values = [
            'title'       => $title,
            'code'        => $code,
            'category'    => $category,
            'brand'       => $brand,
            'status'      => $status,
            'recommended' => $recommended,
            'sort'        => $sort,
        ];
        $form   = new AdminProductSearchForm('search');
        $form->setAttribute('action', $this->url('', ['action' => 'process']));
        $form->setData($values);

        // Set view
        $this->view()->setTemplate('product-index');
        $this->view()->assign('list', $product);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('form', $form);
        $this->view()->assign('config', $config);
    }

    public function processAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form = new AdminProductSearchForm('search');
            $form->setInputFilter(new AdminProductSearchFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $values  = $form->getData();
                $message = __('View filtered products');
                $url     = [
                    'action'      => 'index',
                    'title'       => $values['title'],
                    'code'        => $values['code'],
                    'category'    => $values['category'],
                    'brand'       => $values['brand'],
                    'status'      => $values['status'],
                    'recommended' => $values['recommended'],
                    'sort'        => $values['sort'],
                ];
            } else {
                $message = __('Not valid');
                $url     = [
                    'action' => 'index',
                ];
            }
        } else {
            $message = __('Not set');
            $url     = [
                'action' => 'index',
            ];
        }
        return $this->jump($url, $message);
    }

    public function updateAction()
    {
        // check category
        $categoryCount = Pi::api('category', 'shop')->categoryCount();
        if (!$categoryCount) {
            return $this->redirect()->toRoute(
                '', [
                    'controller' => 'category',
                    'action'     => 'update',
                ]
            );
        }

        // Get id
        $id     = $this->params('id');
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get config
        $option                 = [
            'brand_system' => $config['brand_system']
        ];

        // Find Product
        if ($id) {
            $product = Pi::api('product', 'shop')->getProduct($id);
        }

        // Set form
        $form = new ProductForm('product', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();

            // Set slug
            $slug         = ($data['slug']) ? $data['slug'] : $data['title'];
            $filter       = new Filter\Slug;
            $data['slug'] = $filter($slug);

            // Set code
            if (!empty($data['code'])) {
                $filter       = new Filter\HeadTitle;
                $data['code'] = $filter($data['code']);
            }

            // Form filter
            $form->setInputFilter(new ProductFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Tag
                if (!empty($values['tag'])) {
                    $tag = explode('|', $values['tag']);
                }

                // Category
                $values['category'][] = $values['category_main'];
                $values['category']   = json_encode(array_unique($values['category']));

                // Set seo_title
                $title               = ($values['seo_title']) ? $values['seo_title'] : $values['title'];
                $filter              = new Filter\HeadTitle;
                $values['seo_title'] = $filter($title);

                // Set seo_keywords
                $keywords = ($values['seo_keywords']) ? $values['seo_keywords'] : $values['title'];
                $filter   = new Filter\HeadKeywords;
                $filter->setOptions(
                    [
                        'force_replace_space' => (bool)$this->config('force_replace_space'),
                    ]
                );
                $values['seo_keywords'] = $filter($keywords);

                // Set seo_description
                $description               = ($values['seo_description']) ? $values['seo_description'] : $values['title'];
                $filter                    = new Filter\HeadDescription;
                $values['seo_description'] = $filter($description);

                // Set time
                if (empty($id)) {
                    $values['time_create'] = time();
                    $values['uid']         = Pi::user()->getId();
                }
                $values['time_update'] = time();

                // Set code
                if (empty($values['code'])) {
                    $values['code'] = null;
                }

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('product')->find($id);
                } else {
                    $row = $this->getModel('product')->createRow();
                }
                $row->assign($values);
                $row->save();

                // Category
                Pi::api('category', 'shop')->setLink(
                    $row->id,
                    $row->category,
                    $row->time_create,
                    $row->time_update,
                    $row->price,
                    $row->stock,
                    $row->status,
                    $row->recommended,
                    $row->code
                );

                // Tag
                if (isset($tag) && is_array($tag) && Pi::service('module')->isActive('tag')) {
                    if (empty($id)) {
                        Pi::service('tag')->add($module, $row->id, '', $tag);
                    } else {
                        Pi::service('tag')->update($module, $row->id, '', $tag);
                    }
                }

                // Add / Edit sitemap
                if (Pi::service('module')->isActive('sitemap')) {
                    // Set loc
                    $loc = Pi::url(
                        $this->url(
                            'shop', [
                                'module'     => $module,
                                'controller' => 'product',
                                'slug'       => $values['slug'],
                            ]
                        )
                    );
                    // Update sitemap
                    Pi::api('sitemap', 'sitemap')->singleLink($loc, $row->status, $module, 'product', $row->id);
                }

                // Add log
                $operation = (empty($id)) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('product', $row->id, $operation);

                // Check it save or not
                $message = __('Product data saved successfully.');
                $this->jump(['action' => 'additional', 'id' => $row->id], $message);
            }
        } else {
            if ($id) {
                // Get tag list
                if (Pi::service('module')->isActive('tag')) {
                    $tag = Pi::service('tag')->get($module, $product['id'], '');
                    if (is_array($tag)) {
                        $product['tag'] = implode('|', $tag);
                    }
                }

                // Set data
                $form->setData($product);

                // set nav
                $nav = [
                    'page' => 'update',
                    'type' => 'edit',
                ];

                // Set view
                $this->view()->assign('product', $product);
            } else {
                $nav = [
                    'page' => 'update',
                    'type' => 'add',
                ];
            }
        }

        // Set view
        $this->view()->setTemplate('product-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('nav', $nav);
    }

    public function additionalAction()
    {
        // Get id
        $id     = $this->params('id');
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Find product
        if ($id) {
            //$product = Pi::api('product', 'shop')->getProduct($id);
            $product = $this->getModel('product')->find($id)->toArray();
        } else {
            $this->jump(['action' => 'index'], __('Please select product'));
        }

        // Get attribute field
        $fields = Pi::api('attribute', 'shop')->Get($product['category_main']);

        // Get property
        $property = Pi::api('property', 'shop')->getList();

        $option = [
            'field'          => $fields['attribute'],
            'product_ribbon' => $config['product_ribbon'],
            'video_service'  => $config['video_service'],
            'property'       => $property,
            'propertyValue'  => '',
        ];

        // Check post
        if ($this->request->isPost()) {
            $data = $this->request->getPost();

            // Set form
            $form = new ProductAdditionalForm('product', $option);
            $form->setAttribute('enctype', 'multipart/form-data');
            $form->setInputFilter(new ProductAdditionalFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Set attribute data array
                if (!empty($fields['field'])) {
                    foreach ($fields['field'] as $field) {
                        $attribute[$field]['field'] = $field;
                        $attribute[$field]['data']  = $values[$field];
                    }
                }

                // Set time
                $values['time_update'] = time();

                // Set setting
                $setting = [];
                if ($config['order_discount']) {

                    // Get role list
                    $roles = Pi::service('registry')->Role->read('front');
                    unset($roles['webmaster']);
                    unset($roles['guest']);
                    foreach ($roles as $name => $role) {
                        $setting['discount'][$name] = $values[$name];
                    }
                }
                $values['setting'] = json_encode($setting);

                // Save
                $row = $this->getModel('product')->find($id);
                $row->assign($values);
                $row->save();

                // Set attribute
                if (isset($attribute) && !empty($attribute)) {
                    Pi::api('attribute', 'shop')->Set($attribute, $row->id);
                }

                // Set property
                if (isset($data['property']) && !empty($data['property'])) {
                    /*
                     * Min price for link and product tables update on property api
                     */
                    Pi::api('property', 'shop')->setValue($data['property'], $row->id);
                } else {
                    // Update link
                    $this->getModel('link')->update(
                        ['price' => (int)$values['price']],
                        ['product' => (int)$id]
                    );

                    // Add price log
                    Pi::api('price', 'shop')->addLog((int)$values['price'], (int)$id, 'product');
                }

                // Set video
                if ($config['video_service'] && isset($values['video_list']) && Pi::service('module')->isActive('video')) {
                    Pi::api('service', 'video')->setVideo($values['video_list'], $module, 'product', $product['id']);
                }

                // Add log
                $operation = (empty($id)) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('product', $row->id, $operation);

                // Check it save or not
                if ($row->id) {
                    $message = __('Product data saved successfully.');
                    $this->jump(['controller' => 'attach', 'action' => 'add', 'id' => $row->id], $message);
                }
            }
        } else {
            // Get attribute
            $product = Pi::api('attribute', 'shop')->Form($product);
            // Get property Value
            $option['propertyValue'] = Pi::api('property', 'shop')->getValue($product['id']);
            // Set video
            if ($config['video_service'] && Pi::service('module')->isActive('video')) {
                $videos = Pi::api('service', 'video')->getVideo($module, 'product', $product['id'], false);
                foreach ($videos as $video) {
                    $product['video_list'] = $video['id'];
                }
            }
            // Make setting
            if ($config['order_discount'] && !empty($product['setting']['discount'])) {
                foreach ($product['setting']['discount'] as $name => $value) {
                    $product[$name] = $value;
                }
            }
            // Set form
            $form = new ProductAdditionalForm('product', $option);
            $form->setAttribute('enctype', 'multipart/form-data');
            $form->setData($product);
        }
        // set nav
        $nav = [
            'page' => 'additional',
            'type' => 'edit',
        ];
        // Set view
        $this->view()->setTemplate('product-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('properties', $property);
        $this->view()->assign('product', $product);
        $this->view()->assign('nav', $nav);
    }

    public function recommendAction()
    {
        // Get id and recommended
        $id          = $this->params('id');
        $recommended = $this->params('recommended');
        $return      = [];

        // set product
        $product = $this->getModel('product')->find($id);

        // Check
        if ($product && in_array($recommended, [0, 1])) {

            // Accept
            $product->recommended = $recommended;

            // Save
            if ($product->save()) {
                $return['message']     = sprintf(__('%s set recommended successfully'), $product->title);
                $return['ajaxstatus']  = 1;
                $return['id']          = $product->id;
                $return['recommended'] = $product->recommended;

                // Update recommended
                $this->getModel('link')->update(
                    ['recommended' => $product->recommended],
                    ['product' => $product->id]
                );

                // Add log
                Pi::api('log', 'shop')->addLog('product', $product->id, 'recommend');
            } else {
                $return['message']     = sprintf(__('Error in set recommended for %s product'), $product->title);
                $return['ajaxstatus']  = 0;
                $return['id']          = 0;
                $return['recommended'] = $product->recommended;
            }
        } else {
            $return['message']     = __('Please select product');
            $return['ajaxstatus']  = 0;
            $return['id']          = 0;
            $return['recommended'] = 0;
        }
        return $return;
    }

    public function relatedAction()
    {
        // Get id
        $id           = $this->params('id');
        $product_list = [];

        // Find Product
        if ($id) {
            $product = Pi::api('product', 'shop')->getProduct($id);
        } else {
            return $this->redirect()->toRoute('', ['action' => 'index']);
        }

        // Get related list
        $related_list = Pi::api('related', 'shop')->getListAll($product['id']);

        // Set form
        $form = new RelatedForm('related');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new RelatedFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values       = $form->getData();
                $product_list = Pi::api('related', 'shop')->findList($product['id'], $values);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        }

        // set nav
        $nav = [
            'page' => 'related',
            'type' => 'edit',
        ];

        // Set view
        $this->view()->setTemplate('product-related');
        $this->view()->assign('title', __('Add Related'));
        $this->view()->assign('form', $form);
        $this->view()->assign('product', $product);
        $this->view()->assign('related_list', $related_list);
        $this->view()->assign('product_list', $product_list);
        $this->view()->assign('nav', $nav);
    }

    public function relatedAjaxAction()
    {
        // Get id and related
        $product_id      = $this->params('product_id');
        $product_related = $this->params('product_related');
        $related         = $this->params('related');
        $row             = [];

        // Set return
        $return                = [];
        $return['message']     = __('Please select product');
        $return['ajaxstatus']  = 0;
        $return['id']          = 0;
        $return['storystatus'] = 0;

        // set product
        $product = $this->getModel('product')->find($product_id);

        // Check product
        if ($product && in_array($related, [0, 1])) {
            // add / remove related
            if ($related == 1) {
                if ($product['id'] != $product_related) {
                    // check related
                    $where  = ['product_id' => $product['id'], 'product_related' => $product_related];
                    $select = $this->getModel('related')->select()->where($where)->limit(1);
                    $rowSet = $this->getModel('related')->selectWith($select);
                    if ($rowSet) {
                        $row = $rowSet->toArray();
                    }
                    // Add related
                    if (empty($row)) {
                        // save
                        $row                  = $this->getModel('related')->createRow();
                        $row->product_id      = $product['id'];
                        $row->product_related = $product_related;
                        $row->save();
                        // set return
                        $return['message']       = __('OK Add');
                        $return['ajaxstatus']    = 1;
                        $return['id']            = $product['id'];
                        $return['relatedstatus'] = 1;
                    } else {
                        // set return
                        $return['message']       = __('Error Add , It added before');
                        $return['ajaxstatus']    = 0;
                        $return['id']            = $product['id'];
                        $return['relatedstatus'] = 0;
                    }
                } else {
                    // set return
                    $return['message']       = __('Error , Product and related is same');
                    $return['ajaxstatus']    = 0;
                    $return['id']            = $product['id'];
                    $return['relatedstatus'] = 0;
                }
            } elseif ($related == 0) {
                $this->getModel('related')->delete(['product_id' => $product['id'], 'product_related' => $product_related]);
                $return['message']       = __('OK Remove');
                $return['ajaxstatus']    = 1;
                $return['id']            = $product['id'];
                $return['relatedstatus'] = 1;
            }

            // update related count
            Pi::api('product', 'shop')->relatedCount($product['id']);
        }
        return $return;
    }

    public function priceAction()
    {
        // Set return
        $return = [];

        // Get id
        $id     = $this->params('id');
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get property list
        $propertyList = Pi::api('property', 'shop')->getList();

        // Get product
        $product             = Pi::api('product', 'shop')->getProduct($id);
        $product['property'] = Pi::api('property', 'shop')->getValue($product['id']);

        // Set option
        $option = [
            'id'             => $product['id'],
            'price'          => $product['price_main'],
            'price_discount' => $product['price_discount'],
            'price_shipping' => $product['price_shipping'],
            'stock_type'     => $product['stock_type'],
            'propertyList'   => $propertyList,
            'property'       => $product['property'],
            'type'           => empty($product['property']) ? 'product' : 'property',
            'order_stock'    => $config['order_stock'],
            'stock_type'     => $product['stock_type'],
        ];

        // Set form
        $form = new ProductPriceForm('productPrice', $option);
        if ($this->request->isPost()) {

            // Get information
            $data = $this->request->getPost();
            $form->setInputFilter(new ProductPriceFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Check price set or not
                if (isset($values['price']) && !empty($values['price'])) {

                    // Update product
                    $this->getModel('product')->update(
                        [
                            'price'          => (int)$values['price'],
                            'price_discount' => (int)$values['price_discount'],
                            'price_shipping' => (int)$values['price_shipping'],
                            'stock_type'     => (int)$values['stock_type'],
                        ],
                        ['id' => (int)$id]
                    );

                    // Update link
                    $this->getModel('link')->update(
                        ['price' => (int)$values['price']],
                        ['product' => (int)$id]
                    );
                    // Add price log
                    Pi::api('price', 'shop')->addLog((int)$values['price'], (int)$id, 'product');

                    // return
                    $return['status']        = 1;
                    $return['data']['price'] = Pi::api('api', 'shop')->viewPrice($values['price']);
                    $return['data']['id']    = $id;
                } else {
                    // Update property
                    $priceList = [];

                    // Make property all
                    $propertyValues = $values;
                    unset($propertyValues['price_discount']);
                    unset($propertyValues['price_shipping']);
                    unset($propertyValues['id']);
                    unset($propertyValues['type']);
                    $propertyAll = [];
                    foreach ($propertyValues as $propertyKey => $propertyValue) {
                        $propertySingle                                      = explode('-', $propertyKey);
                        $propertyAll[$propertySingle[1]][$propertySingle[2]] = $propertyValue;
                    }

                    // Update property_value
                    foreach ($propertyAll as $property) {
                        if ($property['price'] > 0) {
                            $priceList[] = (int)$property['price'];
                        }
                        $this->getModel('property_value')->update(
                            ['price' => (int)$property['price']],
                            ['id' => (int)$property['id']]
                        );

                        // Add price log
                        if (isset($property['key']) && !empty($property['key'])) {
                            Pi::api('price', 'shop')->addLog(
                                (int)$property['price'],
                                (int)$id,
                                'property',
                                $property['key']
                            );
                        }
                    }
                    $minPrice = min($priceList);

                    // Update product
                    $this->getModel('product')->update(
                        [
                            'price'          => (int)$minPrice,
                            'price_discount' => (int)$values['price_discount'],
                            'price_shipping' => (int)$values['price_shipping'],
                            'stock_type'     => (int)$values['stock_type'],
                        ],
                        ['id' => (int)$id]
                    );

                    // Update link
                    $this->getModel('link')->update(
                        ['price' => (int)$minPrice],
                        ['product' => (int)$id]
                    );

                    // Add price log
                    Pi::api('price', 'shop')->addLog((int)$minPrice, (int)$id, 'product');

                    // return
                    $return['status']        = 1;
                    $return['data']['price'] = Pi::api('api', 'shop')->viewPrice($minPrice);
                    $return['data']['id']    = $id;
                }
            } else {
                $return['status'] = 0;
                $return['data']   = '';
            }
            return $return;
        } else {
            $form->setAttribute('action', $this->url('', ['action' => 'price', 'id' => $product['id']]));
        }

        // Set view
        $this->view()->setTemplate('system:component/form-popup');
        $this->view()->assign('title', __('Update price and stock'));
        $this->view()->assign('form', $form);
    }

    public function serialAction()
    {
        // Get id
        $id     = $this->params('id');
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get product
        $product = Pi::api('product', 'shop')->getProduct($id);

        // Check
        if (empty($product)) {
            $this->jump(['action' => 'index'], __('Please select product'));
        }

        // Check post
        if ($this->request->isPost()) {
            Pi::api('serial', 'shop')->createSerial($product['id']);
            $this->jump(['action' => 'serial', 'id' => $product['id']], sprintf(__('%s new serial number added'), $config['serial_count']));
        }

        // Get count
        $count               = [];
        $columns             = ['count' => new Expression('count(*)')];
        $whereAll            = ['product' => $product['id']];
        $whereChecked        = ['product' => $product['id'], 'status' => 1];
        $whereNotChecked     = ['product' => $product['id'], 'status' => 0];
        $select              = $this->getModel('serial')->select()->where($whereAll)->columns($columns);
        $count['all']        = $this->getModel('serial')->selectWith($select)->current()->count;
        $select              = $this->getModel('serial')->select()->where($whereChecked)->columns($columns);
        $count['checked']    = $this->getModel('serial')->selectWith($select)->current()->count;
        $select              = $this->getModel('serial')->select()->where($whereNotChecked)->columns($columns);
        $count['notChecked'] = $this->getModel('serial')->selectWith($select)->current()->count;

        // Set view
        $this->view()->setTemplate('product-serial');
        $this->view()->assign('title', sprintf(__('Manage serial number of %s'), $product['title']));
        $this->view()->assign('product', $product);
        $this->view()->assign('count', $count);
        $this->view()->assign('config', $config);
    }

    public function deleteAction()
    {
        // Get information
        $this->view()->setTemplate(false);
        $module = $this->params('module');
        $id     = $this->params('id');
        $row    = $this->getModel('product')->find($id);
        if ($row) {
            $row->status = 5;
            $row->save();

            // update links
            $this->getModel('link')->update(['status' => $row->status], ['product' => $row->id]);

            // Remove sitemap
            if (Pi::service('module')->isActive('sitemap')) {
                $loc = Pi::url(
                    $this->url(
                        'shop', [
                            'module'     => $module,
                            'controller' => 'product',
                            'slug'       => $row->slug,
                        ]
                    )
                );
                Pi::api('sitemap', 'sitemap')->remove($loc);
            }

            // Remove page
            $this->jump(['action' => 'index'], __('This product deleted'));
        }
        $this->jump(['action' => 'index'], __('Please select product'));
    }

    public function exportAction()
    {
        // Get inf0
        $module      = $this->params('module');
        $status      = $this->params('status');
        $category    = $this->params('category');
        $brand       = $this->params('brand');
        $recommended = $this->params('recommended');
        $file        = $this->params('file');
        $start       = $this->params('start', 0);
        $count       = $this->params('count');
        $complete    = $this->params('complete', 0);
        $confirm     = $this->params('confirm', 0);

        // Set path
        $path = Pi::path('upload/shop/csv');
        if (!Pi::service('file')->exists($path . '/index.html')) {
            Pi::service('file')->copy(
                Pi::path('upload/index.html'),
                Pi::path('upload/shop/csv/index.html')
            );
        }

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check request
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form = new AdminProductExportForm('export');
            $form->setInputFilter(new AdminProductExportFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                $url    = [
                    'action'      => 'export',
                    'category'    => $values['category'],
                    'brand'       => $values['brand'],
                    'status'      => $values['status'],
                    'recommended' => $values['recommended'],
                    'sort'        => $values['sort'],
                    'confirm'     => 1,
                    'file'        => sprintf('product-%s-%s', date('Y-m-d-H-i-s'), rand(100, 999)),
                ];
                return $this->jump($url);
            } else {
                $message = __('Not valid');
                $url     = [
                    'action' => 'export',
                ];
                return $this->jump($url, $message);
            }
        } elseif ($confirm == 1) {
            // Get category list
            $categoryList = Pi::registry('categoryList', 'shop')->read();

            // Get products and send
            $order           = ['id ASC'];
            $where           = [];
            $where['id > ?'] = $start;
            if (!empty($recommended)) {
                $where['recommended'] = 1;
            }
            if (!empty($brand)) {
                $where['brand'] = $brand;
            }
            if (!empty($category)) {
                $productId  = [];
                $whereLink  = ['category' => $category];
                $selectLink = $this->getModel('link')->select()->where($whereLink);
                $rowLink    = $this->getModel('link')->selectWith($selectLink);
                foreach ($rowLink as $link) {
                    $productId[] = $link['product'];
                }
                if (!empty($productId)) {
                    $where['id'] = $productId;
                } else {
                    $where['id'] = 0;
                }
            }
            if (!empty($status) && in_array($status, [1, 2, 3, 4, 5])) {
                $where['status'] = $status;
            } else {
                $where['status'] = [1, 2, 3, 4];
            }

            $select = $this->getModel('product')->select()->where($where)->order($order)->limit(50);
            $rowSet = $this->getModel('product')->selectWith($select);

            // Set file
            Pi::service('audit')->attach(
                'product-export', [
                    'file'   => Pi::path(sprintf('upload/shop/csv/%s.csv', $file)),
                    'format' => 'csv',
                ]
            );

            // Make list
            foreach ($rowSet as $row) {
                $product = Pi::api('product', 'shop')->canonizeProduct($row, $categoryList);
                if ($product['attribute']) {
                    $attribute = Pi::api('attribute', 'shop')->Product($product['id'], $product['category_main']);
                    foreach ($attribute['all'] as $attributeSingle) {
                        foreach ($attributeSingle['info'] as $field) {
                            $product[$field['name']] = $field['data'];
                        }
                    }
                }

                // Set key
                if ($complete == 0) {
                    $keys = [];

                    // Set product fields
                    foreach ($product as $key => $value) {
                        $keys[$key] = $key;
                    }

                    // Set attribute fields
                    $whereField  = ['status' => 1];
                    $orderField  = ['order ASC', 'position ASC', 'id DESC'];
                    $selectField = $this->getModel('field')->select()->where($whereField)->order($orderField);
                    $rowSetField = $this->getModel('field')->selectWith($selectField);
                    foreach ($rowSetField as $rowField) {
                        $keys[$rowField->name] = $rowField->name;
                    }

                    Pi::service('audit')->log('product-export', $keys);
                }

                // Set to csv
                Pi::service('audit')->log('product-export', $product);

                // Set extra
                $lastId = $product['id'];
                $complete++;
            }

            // Get count
            if (!$count) {
                $columns = ['count' => new Expression('count(*)')];
                $select  = $this->getModel('product')->select()->columns($columns)->where($where);
                $count   = $this->getModel('product')->selectWith($select)->current()->count;
            }

            // Set complete
            $percent = (100 * $complete) / $count;

            // Set next url
            if ($complete >= $count) {
                $nextUrl     = '';
                $downloadUrl = sprintf('%s?upload/shop/csv/%s.csv', Pi::url('www/script/download.php'), $file);
            } else {
                $nextUrl     = Pi::url(
                    $this->url(
                        '', [
                            'action'      => 'export',
                            'start'       => $lastId,
                            'count'       => $count,
                            'complete'    => $complete,
                            'confirm'     => $confirm,
                            'file'        => $file,
                            'category'    => $category,
                            'brand'       => $brand,
                            'status'      => $status,
                            'recommended' => $recommended,
                        ]
                    )
                );
                $downloadUrl = '';
            }

            $info = [
                'start'       => $lastId,
                'count'       => $count,
                'complete'    => $complete,
                'percent'     => $percent,
                'nextUrl'     => $nextUrl,
                'downloadUrl' => $downloadUrl,
            ];

            $percent = ($percent > 99 && $percent < 100) ? (intval($percent) + 1) : intval($percent);

        } else {
            // Set info
            $info        = [];
            $percent     = 0;
            $nextUrl     = '';
            $downloadUrl = '';

            // Set form
            $form = new AdminProductExportForm('export');

            // Set filter
            $filter = function ($fileinfo) {
                if (!$fileinfo->isFile()) {
                    return false;
                }
                $filename = $fileinfo->getFilename();
                if ('index.html' == $filename) {
                    return false;
                }
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if ($ext != 'csv') {
                    return false;
                }
                return $filename;
            };

            // Get file list
            $fileList = Pi::service('file')->getList($path, $filter);

            // Set view
            $this->view()->assign('form', $form);
            $this->view()->assign('fileList', $fileList);
        }

        // Check convert to excel
        $checkExcel = Pi::api('CSVToExcelConverter', 'shop')->check();

        // Set view
        $this->view()->setTemplate('product-export');
        $this->view()->assign('config', $config);
        $this->view()->assign('nextUrl', $nextUrl);
        $this->view()->assign('downloadUrl', $downloadUrl);
        $this->view()->assign('percent', $percent);
        $this->view()->assign('info', $info);
        $this->view()->assign('confirm', $confirm);
        $this->view()->assign('checkExcel', $checkExcel);
    }

    public function downloadAction()
    {
        $file = $this->params('file');
        $type = $this->params('type');

        $csvFile   = $file . '.csv';
        $csvPath   = Pi::path('upload/shop/csv/') . $csvFile;
        $excelFile = $file . '.xlsx';
        $excelPath = Pi::path('upload/shop/csv/') . $excelFile;

        switch ($type) {
            case 'xlsx':
                if (Pi::service('file')->exists($csvPath)) {
                    // Check excel file exist
                    if (!Pi::service('file')->exists($excelPath)) {
                        try {
                            Pi::api('CSVToExcelConverter', 'shop')->convert($csvPath, $excelPath);
                        } catch (Exception $e) {
                            echo $e->getMessage();
                        }
                    }
                    $url = sprintf(
                        '%s?upload/shop/csv/%s',
                        Pi::url('www/script/download.php'),
                        $excelFile
                    );
                }
                break;

            default:
            case 'csv':
                if (Pi::service('file')->exists($csvPath)) {
                    $url = sprintf(
                        '%s?upload/shop/csv/%s',
                        Pi::url('www/script/download.php'),
                        $csvFile
                    );
                }
                break;
        }

        // Set url
        return $this->redirect()->toUrl($url);
    }
}