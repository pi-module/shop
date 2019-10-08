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

namespace Module\Shop\Controller\Front;

use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Zend\Db\Sql\Predicate\Expression;

class JsonController extends IndexController
{
    public function searchAction()
    {
        // Get info from url
        $module        = $this->params('module');
        $page          = $this->params('page', 1);
        $title         = $this->params('title');
        $code          = $this->params('code');
        $category      = $this->params('category');
        $categoryTitle = $this->params('categoryTitle');
        $tag           = $this->params('tag');
        $favourite     = $this->params('favourite');
        $recommended   = $this->params('recommended');
        $related       = $this->params('related');
        $product       = $this->params('product');
        $limit         = $this->params('limit');
        $order         = $this->params('order');

        // Set has search result
        $hasSearchResult = true;

        // Clean title
        if (Pi::service('module')->isActive('search') && isset($title) && !empty($title)) {
            $title = Pi::api('api', 'search')->parseQuery($title);
        } elseif (isset($title) && !empty($title)) {
            $title = _strip($title);
        } else {
            $title = '';
        }

        // Clean category Title
        if (Pi::service('module')->isActive('search') && isset($categoryTitle) && !empty($categoryTitle)) {
            $categoryTitle = Pi::api('api', 'search')->parseQuery($categoryTitle);
        } elseif (isset($categoryTitle) && !empty($categoryTitle)) {
            $categoryTitle = _strip($categoryTitle);
        } else {
            $categoryTitle = '';
        }

        // Clean code
        if (isset($code) && !empty($code)) {
            $filter = new Filter\HeadTitle;
            $code   = $filter($code);
        } else {
            $code = '';
        }

        // Clean params
        $paramsClean = [];
        foreach ($_GET as $key => $value) {
            $key               = _strip($key);
            $value             = _strip($value);
            $paramsClean[$key] = $value;
        }

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set empty result
        $result = [
            'products'   => [],
            'categories' => [],
            'filterList' => [],
            'paginator'  => [],
            'condition'  => [],
            'price'      => [],
        ];

        // Set where link
        $whereLink = ['status' => 1];
        if (!empty($recommended) && $recommended == 1) {
            $whereLink['recommended'] = 1;
        }
        if (!empty($code)) {
            $whereLink['code LIKE ?'] = '%' . $code . '%';
        }

        // Set page title
        $pageTitle = __('List of products');

        // Set order
        switch ($order) {
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

        // Get related
        if (!empty($related) && $related == 1 && !empty($product) && intval($product) > 0) {
            $productSingle = Pi::api('product', 'shop')->getProductLight(intval($product));
            $category      = $productSingle['category_main'];
        }

        // Get category information from model
        if (!empty($category)) {
            // Get category
            if (is_numeric($category) && intval($category) > 0) {
                $category = Pi::api('category', 'shop')->getCategory(intval($category));
            } else {
                $category = Pi::api('category', 'shop')->getCategory($category, 'slug');
            }
            // Check category
            if (!$category || $category['status'] != 1) {
                return $result;
            }
            // category list
            $categories = Pi::api('category', 'shop')->categoryList($category['id']);
            // Get id list
            $categoryIDList   = [];
            $categoryIDList[] = $category['id'];
            foreach ($categories as $singleCategory) {
                $categoryIDList[] = $singleCategory['id'];
            }
            // Set page title
            $pageTitle = sprintf(__('List of products on %s category'), $category['title']);
        }

        // Get tag list
        if (!empty($tag)) {
            $productIDTag = [];
            // Check favourite
            if (!Pi::service('module')->isActive('tag')) {
                return $result;
            }
            // Get id from tag module
            $tagList = Pi::service('tag')->getList($tag, $module);
            foreach ($tagList as $tagSingle) {
                $productIDTag[] = $tagSingle['item'];
            }
            // Set header and title
            $pageTitle = sprintf(__('All products from %s'), $tag);
        }

        // Get favourite list
        if (!empty($favourite) && $favourite == 1) {
            // Check favourite
            if (!Pi::service('module')->isActive('favourite')) {
                return $result;
            }
            // Get uid
            $uid = Pi::user()->getId();
            // Check user
            if (!$uid) {
                return $result;
            }
            // Get id from favourite module
            $productIDFavourite = Pi::api('favourite', 'favourite')->userFavourite($uid, $module);
            // Set page title
            $pageTitle = ('All favourite products by you');
        }

        // Get search form
        $filterList   = Pi::api('attribute', 'shop')->filterList();
        $categoryList = Pi::registry('categoryList', 'shop')->read();

        // Set product ID list
        $checkTitle     = false;
        $checkAttribute = false;
        $productIDList  = [
            'title'     => [],
            'attribute' => [],
        ];

        // Check title from product table
        if (isset($title) && !empty($title)) {
            $checkTitle = true;
            $titles     = is_array($title) ? $title : [$title];
            $columns    = ['id'];
            $select     = $this->getModel('product')->select()->columns($columns)->where(
                function ($where) use ($titles, $recommended, $code) {
                    $whereMain        = clone $where;
                    $whereTitleKey    = clone $where;
                    $whereSubTitleKey = clone $where;

                    // Set where Main
                    $whereMain->equalTo('status', 1);
                    if (!empty($recommended) && $recommended == 1) {
                        $whereMain->equalTo('recommended', 1);
                    }
                    if (!empty($code)) {
                        $whereMain->like('code', '%' . $code . '%');
                    }

                    // Set where title
                    foreach ($titles as $term) {
                        $whereTitleKey->like('title', '%' . $term . '%')->and;
                    }

                    // Set where  subtitle
                    foreach ($titles as $term) {
                        $whereSubTitleKey->like('subtitle', '%' . $term . '%')->and;
                    }

                    $where->andPredicate($whereMain)->andPredicate($whereTitleKey)->orPredicate($whereSubTitleKey);
                }
            )->order($order);
            $rowset     = $this->getModel('product')->selectWith($select);
            foreach ($rowset as $row) {
                $productIDList['title'][$row->id] = $row->id;
            }
        }

        // Check attribute
        if (!empty($paramsClean)) {
            // Make attribute list
            $attributeList = [];
            foreach ($filterList as $filterSingle) {
                if (isset($paramsClean[$filterSingle['name']]) && !empty($paramsClean[$filterSingle['name']])) {
                    $attributeList[$filterSingle['name']] = [
                        'field' => $filterSingle['id'],
                        'data'  => $paramsClean[$filterSingle['name']],
                    ];
                }
            }
            // Search on attribute
            if (!empty($attributeList)) {
                $checkAttribute = true;
                $column         = ['product'];
                foreach ($attributeList as $attributeSingle) {
                    $where  = [
                        'field' => $attributeSingle['field'],
                        'data'  => $attributeSingle['data'],
                    ];
                    $select = $this->getModel('field_data')->select()->where($where)->columns($column);
                    $rowset = $this->getModel('field_data')->selectWith($select);
                    foreach ($rowset as $row) {
                        $productIDList['attribute'][$row->product] = $row->product;
                    }
                }
            }
        }

        // Set info
        $product = [];
        $count   = 0;

        $columns = ['product' => new Expression('DISTINCT product'), '*'];
        $limit   = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);
        $offset  = (int)($page - 1) * $limit;

        // Set category on where link
        if (isset($categoryIDList) && !empty($categoryIDList)) {
            $whereLink['category'] = $categoryIDList;
        }

        // Set product on where link from title and attribute
        if ($checkTitle && $checkAttribute) {
            if (!empty($productIDList['title']) && !empty($productIDList['attribute'])) {
                $whereLink['product'] = array_intersect($productIDList['title'], $productIDList['attribute']);
            } else {
                $hasSearchResult = false;
            }
        } elseif ($checkTitle) {
            if (!empty($productIDList['title'])) {
                $whereLink['product'] = $productIDList['title'];
            } else {
                $hasSearchResult = false;
            }
        } elseif ($checkAttribute) {
            if (!empty($productIDList['attribute'])) {
                $whereLink['product'] = $productIDList['attribute'];
            } else {
                $hasSearchResult = false;
            }
        }

        // Set favourite products on where link
        if (!empty($favourite) && $favourite == 1 && isset($productIDFavourite)) {
            if (isset($whereLink['product']) && !empty($whereLink['product'])) {
                $whereLink['product'] = array_intersect($productIDFavourite, $whereLink['product']);
            } elseif (!isset($whereLink['product']) || empty($whereLink['product'])) {
                $whereLink['product'] = $productIDFavourite;
            } else {
                $hasSearchResult = false;
            }
        }

        // Set tag products on where link
        if (!empty($tag) && isset($productIDTag)) {
            if (isset($whereLink['product']) && !empty($whereLink['product'])) {
                $whereLink['product'] = array_intersect($productIDTag, $whereLink['product']);
            } elseif (!isset($whereLink['product']) || empty($whereLink['product'])) {
                $whereLink['product'] = $productIDTag;
            } else {
                $hasSearchResult = false;
            }
        }

        // Get max price
        if ($config['view_price_filter']) {
            $columnsPrice = ['id', 'price'];
            $limitPrice   = 1;
            $maxPrice     = 1000;
            $minPrice     = 0;

            $orderPrice  = ['price DESC', 'id DESC'];
            $selectPrice = $this->getModel('link')->select()->where($whereLink)->columns($columnsPrice)->order($orderPrice)->limit($limitPrice);
            $rowPrice    = $this->getModel('link')->selectWith($selectPrice)->current();
            if ($rowPrice) {
                $rowPrice = $rowPrice->toArray();
                $maxPrice = $rowPrice['price'];
            }

            $orderPrice  = ['price ASC', 'id ASC'];
            $selectPrice = $this->getModel('link')->select()->where($whereLink)->columns($columnsPrice)->order($orderPrice)->limit($limitPrice);
            $rowPrice    = $this->getModel('link')->selectWith($selectPrice)->current();
            if ($rowPrice) {
                $rowPrice = $rowPrice->toArray();
                $minPrice = $rowPrice['price'];
            }

            // Get select min price
            $minSelect = $minPrice;
            if (isset($paramsClean['minPrice']) && !empty($paramsClean['minPrice'])) {
                $minSelect = $paramsClean['minPrice'];
                if ($minSelect > $minPrice) {
                    $whereLink['price >= ?'] = $minSelect;
                }
            }

            // Get select max price
            $maxSelect = $maxPrice;
            if (isset($paramsClean['maxPrice']) && !empty($paramsClean['maxPrice'])) {
                $maxSelect = $paramsClean['maxPrice'];
                if ($maxSelect < $maxPrice) {
                    $whereLink['price <= ?'] = $maxSelect;
                }
            }
        } else {
            $minPrice  = 0;
            $maxPrice  = 0;
            $minSelect = 0;
            $maxSelect = 0;
        }

        // Check has Search Result
        if ($hasSearchResult) {
            // Get info from link table
            $select = $this->getModel('link')->select()->where($whereLink)->columns($columns)->order($order)->offset($offset)->limit($limit);
            $rowset = $this->getModel('link')->selectWith($select)->toArray();
            foreach ($rowset as $id) {
                $productIDSelect[] = $id['product'];
            }

            // Get list of product
            if (!empty($productIDSelect)) {
                $where  = ['status' => 1, 'id' => $productIDSelect];
                $select = $this->getModel('product')->select()->where($where)->order($order);
                $rowset = $this->getModel('product')->selectWith($select);
                foreach ($rowset as $row) {
                    $product[] = Pi::api('product', 'shop')->canonizeProductFilter($row, $categoryList, $filterList);
                }
            }

            // Get count
            $columnsCount = ['count' => new Expression('count(DISTINCT `product`)')];
            $select       = $this->getModel('link')->select()->where($whereLink)->columns($columnsCount);
            $count        = $this->getModel('link')->selectWith($select)->current()->count;
        }

        // Search on category
        $categoryList = [];
        if (!empty($categoryTitle)) {
            $whereCategory                 = ['status' => 1];
            $whereCategory['title LIKE ?'] = '%' . $categoryTitle . '%';
            $orderCategory                 = ['title DESC', 'id DESC'];
            $select                        = $this->getModel('category')->select()->where($whereCategory)->order($orderCategory)->offset($offset)->limit(
                $limit
            );
            $rowset                        = $this->getModel('category')->selectWith($select);
            foreach ($rowset as $row) {
                $categoryList[] = Pi::api('category', 'shop')->canonizeCategory($row);
            }
        }

        // Set column class
        switch ($config['view_column']) {
            case 1:
                $columnSize = 'col-lg-12 col-md-12 col-12';
                break;

            case 2:
                $columnSize = 'col-lg-6 col-md-6 col-12';
                break;

            case 3:
                $columnSize = 'col-lg-4 col-md-4 col-12';
                break;

            case 4:
                $columnSize = 'col-lg-3 col-md-3 col-12';
                break;

            case 6:
                $columnSize = 'col-lg-2 col-md-2 col-12';
                break;

            default:
                $columnSize = 'col-lg-3 col-md-3 col-12';
                break;
        }

        // Set result
        $result = [
            'products'   => $product,
            'categories' => $categoryList,
            'filterList' => $filterList,
            'paginator'  => [
                'count' => $count,
                'limit' => $limit,
                'page'  => $page,
            ],
            'condition'  => [
                'title'       => $pageTitle,
                'urlCompare'  => Pi::url($this->url('', ['controller' => 'compare'])),
                'priceFilter' => $config['view_price_filter'],
                'addToCart'   => $config['view_add_to_cart'],
                'columnSize'  => $columnSize,
            ],
            'price'      => [
                'minValue'    => intval($minPrice),
                'maxValue'    => intval($maxPrice),
                'minSelect'   => intval($minSelect),
                'maxSelect'   => intval($maxSelect),
                'step'        => intval(($maxPrice - $minPrice) / 10),
                'rightToLeft' => false,
            ],
        ];

        return $result;
    }

    public function brandAction()
    {
        $module = $this->params('module');
        $page   = $this->params('page', 1);
        $limit  = $this->params('limit');
        $form   = $this->params('form');
        //$uid = $this->params('uid');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        $brand  = [];
        $limit  = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);
        $offset = (int)($page - 1) * $limit;

        $where = ['status' => 1, 'type' => 'brand'];
        $order = ['display_order ASC', 'title ASC', 'id DESC'];

        if ($form) {
            $where['id'] = Pi::api('form', 'forms')->getAllowIdList($form);
        }

        $select = $this->getModel('category')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('category')->selectWith($select);
        foreach ($rowset as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $count          = Pi::api('product', 'shop')->getBrandCount($categorySingle['id']);
            $hasNew         = Pi::api('product', 'shop')->getBrandHasNew($categorySingle['id']);
            $brand[]        = [
                'id'        => $categorySingle['id'],
                'parent'    => $categorySingle['parent'],
                'title'     => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl'  => $categorySingle['thumbUrl'],
                'count'     => _number($count),
                'is_new'    => $hasNew,
            ];
        }

        // Get count
        $columnsCount = ['count' => new Expression('count(*)')];
        $select       = $this->getModel('category')->select()->where($where)->columns($columnsCount);
        $count        = $this->getModel('category')->selectWith($select)->current()->count;

        $result = [
            'brands'    => $brand,
            'paginator' => [
                'count' => $count,
                'limit' => $limit,
                'page'  => $page,
            ],
            'form'      => $form,
        ];

        return $result;
    }

    public function categoryAction()
    {
        $module  = $this->params('module');
        $page    = $this->params('page', 1);
        $limit   = $this->params('limit');
        $parent  = $this->params('parent');
        $tree    = $this->params('tree');
        $product = $this->params('product');
        $brand   = $this->params('brand');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        $category = [];
        $products = [];
        $limit    = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);
        $offset   = (int)($page - 1) * $limit;

        // Set where
        $where = ['status' => 1, 'type' => 'category'];
        if (intval($parent) > 0) {
            $where['parent'] = intval($parent);
        }

        // Check $brand
        if (intval($brand) > 0) {
            $whereBrand   = ['brand' => intval($brand)];
            $columnsBrand = ['category' => new Expression('DISTINCT `category_main`')];
            $select       = $this->getModel('product')->select()->where($whereBrand)->columns($columnsBrand);
            $rowset       = $this->getModel('product')->selectWith($select)->toArray();
            foreach ($rowset as $id) {
                $categoryIDSelect[] = $id['category'];
            }
            $where['id'] = $categoryIDSelect;
        }

        // Select
        $categoryId = [];
        $order      = ['parent ASC', 'id DESC'];
        $select     = $this->getModel('category')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset     = $this->getModel('category')->selectWith($select);
        foreach ($rowset as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $categoryId[]   = $row->id;
            $category[]     = [
                'id'        => $categorySingle['id'],
                'parent'    => $categorySingle['parent'],
                'title'     => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl'  => $categorySingle['thumbUrl'],
            ];
        }

        if ($product == 1 && !empty($categoryId)) {
            $products     = [];
            $whereProduct = ['status' => 1, 'category_main' => $categoryId];
            $orderProduct = ['title DESC', 'id DESC'];
            $select       = $this->getModel('product')->select()->where($whereProduct)->order($orderProduct);
            $rowset       = $this->getModel('product')->selectWith($select);
            foreach ($rowset as $row) {
                $productSingle = Pi::api('product', 'shop')->canonizeProductLight($row);
                $products[]    = [
                    'id'        => $productSingle['id'],
                    'category'  => $productSingle['category_main'],
                    'brand'     => $productSingle['brand'],
                    'title'     => $productSingle['title'],
                    'mediumUrl' => $productSingle['mediumUrl'],
                    'thumbUrl'  => $productSingle['thumbUrl'],
                    'is_new'    => $productSingle['is_new'],
                ];
            }
        } elseif ($tree == 1) {
            // Set as tree
            $category = Pi::api('category', 'shop')->makeTree($category);
        }

        // Get count
        $columnsCount = ['count' => new Expression('count(*)')];
        $select       = $this->getModel('category')->select()->where($where)->columns($columnsCount);
        $count        = $this->getModel('category')->selectWith($select)->current()->count;

        // Set result
        $result = [
            'categories' => $category,
            'products'   => $products,
            'paginator'  => [
                'count' => $count,
                'limit' => $limit,
                'page'  => $page,
            ],
        ];

        return $result;
    }

    public function productAllAction()
    {
        // Get info from url
        $update = $this->params('update', 0);
        // Check password
        if (!$this->checkPassword()) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('Password not set or wrong'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Check
        $where = [
            'status'          => 1,
            'time_update > ?' => $update,
        ];
        // Get story List
        $productList = $this->productJsonList($where);
        // Set view
        return $productList;
    }

    public function productCategoryAction()
    {
        // Get info from url
        $categoryMain = $this->params('id');
        $update       = $this->params('update', 0);
        // Check password
        if (!$this->checkPassword()) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('Password not set or wrong'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get category information from model by category_main id
        $categoryMain = $this->getModel('category')->find($categoryMain);
        $categoryMain = Pi::api('category', 'shop')->canonizeCategory($categoryMain);
        // Check category
        if (!$categoryMain || $categoryMain['status'] != 1) {
            $productList = [];
        } else {
            // Set story info
            $where = [
                'status'          => 1,
                'category'        => $categoryMain['id'],
                'time_update > ?' => $update,
            ];
            // Get story List
            $productList = $this->productJsonList($where);
        }
        // Set view
        return $productList;
    }

    public function productSingleAction()
    {
        // Get info from url
        $id     = $this->params('id');
        $module = $this->params('module');
        // Check password
        if (!$this->checkPassword()) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('Password not set or wrong'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Find product
        $product = $this->getModel('product')->find($id);
        $product = Pi::api('product', 'shop')->canonizeProductJson($product);
        // Check item
        if (!$product || $product['status'] != 1) {
            $productSingle = [];
        } else {
            $productSingle = $product;
            if ($product['attribute'] && $config['view_attribute']) {
                $attributes = Pi::api('attribute', 'shop')->Product($product['id'], $product['category_main']);
                foreach ($attributes['all'] as $attributesAll) {
                    foreach ($attributesAll['info'] as $attribute) {
                        if (!empty($attribute['name'])) {
                            $productSingle['attribute-' . $attribute['name']]            = $attribute['data'];
                            $productSingle['attribute-' . $attribute['name'] . '-title'] = $attribute['title'];
                        }
                    }
                }
            }
            // Get attach file
            $attach = Pi::api('product', 'shop')->AttachList($product['id']);
            // Set output array
            $i = 1;
            // generate images
            foreach ($attach['image'] as $image) {
                $i++;
                $productSingle['extra-image-' . $i] = $image['largeUrl'];
            }
        }

        // Check brand
        if ($product['brand'] > 0) {
            $brand                       = Pi::api('category', 'shop')->getCategory($product['brand']);
            $productSingle['brandId']    = $brand['id'];
            $productSingle['brandTitle'] = $brand['title'];
            $productSingle['brandImage'] = $brand['thumbUrl'];
        }

        $productSingle = [$productSingle];
        // Set view
        return $productSingle;
    }

    public function categorySingleAction()
    {
        // Get info from url
        $id = $this->params('id');
        // Check password
        if (!$this->checkPassword()) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('Password not set or wrong'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get category
        $category = Pi::api('category', 'shop')->getCategory($id);

        $categorySingle = [
            'id'          => $category['id'],
            'title'       => $category['title'],
            'largeUrl'    => $category['largeUrl'],
            'mediumUrl'   => $category['mediumUrl'],
            'thumbUrl'    => $category['thumbUrl'],
            'description' => $category['text_summery'] . $category['text_description'],
        ];

        $categorySingle = [$categorySingle];
        // Set view
        return $categorySingle;
    }

    public function checkPassword()
    {
        // Get info from url
        $module   = $this->params('module');
        $password = $this->params('password');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check password
        if ($config['json_check_password']) {
            if ($config['json_password'] == $password) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /* public function visitAction()
    {
        $brand = array();
        $where = array('status' => 1, 'type' => 'brand');
        $order = array('display_order ASC', 'title ASC', 'id DESC');
        $select = $this->getModel('category')->select()->where($where)->order($order)->limit(15);
        $rowset = $this->getModel('category')->selectWith($select);
        foreach ($rowset as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $brand[] = array(
                'id' => $categorySingle['id'],
                'parent' => $categorySingle['parent'],
                'title' => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl' => $categorySingle['thumbUrl'],
            );
        }

        $days = array();
        $daysTitle = array(
            'Saturday' => 'شنبه',
            'Sunday' => 'یکشنبه',
            'Monday' => 'دوشنبه',
            'Tuesday' => 'سه شنبه',
            'Wednesday' => 'چهارشنبه',
            'Thursday' => 'پنجشنبه',
            'Friday' => 'جمعه',
        );

        $id = 1;
        $timestamp = strtotime('today');
        for ($i = 0; $i < 7; $i++) {
            $day = strftime('%A', $timestamp);
            $days[] = array(
                'id' => $id++,
                'name' => $day,
                'title' => $daysTitle[$day],
            );
            $timestamp = strtotime('+1 day', $timestamp);
        }

        // Set result
        $result = array(
            'day' => $days,
            'brands' => $brand,
        );

        return $result;
    } */
}