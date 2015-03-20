<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
namespace Module\Shop\Controller\Admin;

use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Pi\File\Transfer\Upload;
use Module\Shop\Form\ProductForm;
use Module\Shop\Form\ProductFilter;
use Module\Shop\Form\ProductAdditionalForm;
use Module\Shop\Form\ProductAdditionalFilter;
use Module\Shop\Form\RelatedForm;
use Module\Shop\Form\RelatedFilter;
use Module\Shop\Form\AdminSearchForm;
use Module\Shop\Form\AdminSearchFilter;
use Zend\Json\Json;

class ProductController extends ActionController
{
    /**
     * Product Image Prefix
     */
    protected $ImageProductPrefix = 'product_';

    /**
     * Product Columns
     */
    protected $productColumns = array(
    	'id', 'title', 'slug', 'category', 'text_summary', 'text_description', 'seo_title', 
        'seo_keywords', 'seo_description', 'status', 'time_create', 'time_update', 
        'uid', 'hits', 'sales', 'image', 'path', 'comment', 'point', 'count', 
        'favorite', 'attach', 'attribute', 'related', 'recommended', 'category_main',
        'stock', 'stock_alert', 'stock_type', 'price', 'price_discount', 'price_title'
    );

    /**
     * index Action
     */
	public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $status = $this->params('status');
        $category = $this->params('category');
        $title = $this->params('title');
        // Set info
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $order = array('time_create DESC', 'id DESC');
        $limit = intval($this->config('admin_perpage'));
        $product = array();
        // Get
        if (empty($title)) {
            // Set where
            $whereLink = array();
            if (!empty($status)) {
                $whereLink['status'] = $status;
            }
            if (!empty($category)) {
                $whereLink['category'] = $category;
            }
            $columnsLink = array('product' => new \Zend\Db\Sql\Predicate\Expression('DISTINCT product'));
            // Get info from link table
            $select = $this->getModel('link')->select()->where($whereLink)->columns($columnsLink)->order($order)->offset($offset)->limit($limit);
            $rowset = $this->getModel('link')->selectWith($select)->toArray();
            // Make list
            foreach ($rowset as $id) {
                $productId[] = $id['product'];
            }
            // Set info
            $whereProduct = array('id' => $productId);
        } else {
            $whereProduct = array();
            $whereProduct['title LIKE ?'] = '%' . $title . '%';
        }
        // Get list of product
        $select = $this->getModel('product')->select()->where($whereProduct)->order($order);
        $rowset = $this->getModel('product')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row);
        }
        // Set count
        if (empty($title)) {
            $columnsLink = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(DISTINCT `product`)'));
            $select = $this->getModel('link')->select()->where($whereLink)->columns($columnsLink);
            $count = $this->getModel('link')->selectWith($select)->current()->count;
        } else {
            $columnsLink = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
            $select = $this->getModel('product')->select()->where($whereProduct)->columns($columnsLink);
            $count = $this->getModel('product')->selectWith($select)->current()->count;
        }    
        // Set paginator
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => 'product',
                'action'        => 'index',
                'category'      => $category,
                'status'        => $status,
                'title'         => $title,
            )),
        ));
        // Set form
        $values = array(
            'title' => $title,
        );
        $form = new AdminSearchForm('search');
        $form->setAttribute('action', $this->url('', array('action' => 'process')));
        $form->setData($values);
        // Set view
        $this->view()->setTemplate('product_index');
        $this->view()->assign('list', $product);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('form', $form);
    }

    public function processAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form = new AdminSearchForm('search');
            $form->setInputFilter(new AdminSearchFilter());
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                $message = __('View filtered products');
                $url = array(
                    'action' => 'index',
                    'title' => $values['title'],
                );
            } else {
                $message = __('Not valid');
                $url = array(
                    'action' => 'index',
                );
            }
        } else {
            $message = __('Not set');
            $url = array(
                'action' => 'index',
            );
        } 
        return $this->jump($url, $message);  
    }

    /**
     * update Action
     */
    public function updateAction()
    {
        // check category
        $categoryCount = Pi::api('category', 'shop')->categoryCount();
        if (!$categoryCount) {
            return $this->redirect()->toRoute('', array(
                'controller' => 'category',
                'action' => 'update'
            ));
        }
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        $option = array();
        // Find Product
        if ($id) {
            $product = $this->getModel('product')->find($id)->toArray();
            $product['category'] = Json::decode($product['category']);
            if ($product['image']) {
                $thumbUrl = sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $product['path'], $product['image']);
                $option['thumbUrl'] = Pi::url($thumbUrl);
                $option['removeUrl'] = $this->url('', array('action' => 'remove', 'id' => $product['id']));
            }
        }
        // Set form
        $form = new ProductForm('product', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $file = $this->request->getFiles();
            // Set slug
            $slug = ($data['slug']) ? $data['slug'] : $data['title'];
            $filter = new Filter\Slug;
            $data['slug'] = $filter($slug);
            // Form filter
            $form->setInputFilter(new ProductFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
                // Tag
                if (!empty($values['tag'])) {
                    $tag = explode('|', $values['tag']);
                }
                // upload image
                if (!empty($file['image']['name'])) {
                    // Set upload path
                    $values['path'] = sprintf('%s/%s', date('Y'), date('m'));
                    $originalPath = Pi::path(sprintf('upload/%s/original/%s', $this->config('image_path'), $values['path']));
                    // Image name
                    $imageName = Pi::api('image', 'shop')->rename($file['image']['name'], $this->ImageProductPrefix, $values['path']);
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($originalPath);
                    $uploader->setRename($imageName);
                    $uploader->setExtension($this->config('image_extension'));
                    $uploader->setSize($this->config('image_size'));
                    if ($uploader->isValid()) {
                        $uploader->receive();
                        // Get image name
                        $values['image'] = $uploader->getUploaded('image');
                        // process image
                        Pi::api('image', 'shop')->process($values['image'], $values['path']);
                    } else {
                        $this->jump(array('action' => 'update'), __('Problem in upload image. please try again'));
                    }
                } elseif (!isset($values['image'])) {
                    $values['image'] = '';  
                }
            	// Set just product fields
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->productColumns)) {
                        unset($values[$key]);
                    }
                }
                // Category
                $values['category'] = Json::encode(array_unique($values['category']));
                // Set seo_title
                $title = ($values['seo_title']) ? $values['seo_title'] : $values['title'];
                $filter = new Filter\HeadTitle;
                $values['seo_title'] = $filter($title);
                // Set seo_keywords
                $keywords = ($values['seo_keywords']) ? $values['seo_keywords'] : $values['title'];
                $filter = new Filter\HeadKeywords;
                $filter->setOptions(array(
                    'force_replace_space' => (bool) $this->config('force_replace_space'),
                ));
                $values['seo_keywords'] = $filter($keywords);
                // Set seo_description
                $description = ($values['seo_description']) ? $values['seo_description'] : $values['title'];
                $filter = new Filter\HeadDescription;
                $values['seo_description'] = $filter($description);
                // Set time
                if (empty($values['id'])) {
                    $values['time_create'] = time();
                    $values['uid'] = Pi::user()->getId();
                }
                $values['time_update'] = time();
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('product')->find($values['id']);
                } else {
                    $row = $this->getModel('product')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Category
                Pi::api('category', 'shop')->setLink($row->id, $row->category, $row->time_create, $row->time_update, $row->price, $row->stock, $row->status);
                // Tag
                if (isset($tag) && is_array($tag) && Pi::service('module')->isActive('tag')) {
                    if (empty($values['id'])) {
                        Pi::service('tag')->add($module, $row->id, '', $tag);
                    } else {
                        Pi::service('tag')->update($module, $row->id, '', $tag);
                    }
                }
                // Add / Edit sitemap
                if (Pi::service('module')->isActive('sitemap')) {
                    // Set loc
                    $loc = Pi::url($this->url('shop', array(
                        'module'      => $module, 
                        'controller'  => 'product', 
                        'slug'        => $values['slug']
                    )));
                    // Update sitemap
                    Pi::api('sitemap', 'sitemap')->singleLink($loc, $row->status, $module, 'product', $row->id);         
                }
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('product', $row->id, $operation);
                // Check it save or not
                $message = __('Product data saved successfully.');
                $this->jump(array('action' => 'additional', 'id' => $row->id), $message);
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
            }
        }   
        // Set view
        $this->view()->setTemplate('product_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add product'));
    }

    public function additionalAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Find product
        if ($id) {
            $product = $this->getModel('product')->find($id)->toArray();
        } else {
            $this->jump(array('action' => 'index'), __('Please select product'));
        }
        // Get attribute field
        $fields = Pi::api('attribute', 'shop')->Get($product['category_main']);
        $option['field'] = $fields['attribute'];
        // Set form
        $form = new ProductAdditionalForm('product', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ProductAdditionalFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set attribute data array
                if (!empty($fields['field'])) {
                    foreach ($fields['field'] as $field) {
                        $attribute[$field]['field'] = $field;
                        $attribute[$field]['data'] = $values[$field];
                    }
                }
                // Set just product fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->productColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set time
                $values['time_update'] = time();
                // Save
                $row = $this->getModel('product')->find($values['id']);
                $row->assign($values);
                $row->save();
                // attribute
                if (!empty($attribute)) {
                    Pi::api('attribute', 'shop')->Set($attribute, $row->id);
                }
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('product', $row->id, $operation);
                // Check it save or not
                if ($row->id) {
                    $message = __('Product data saved successfully.');
                    $this->jump(array('controller' => 'attach', 'action' => 'add', 'id' => $row->id), $message);
                }
            }   
        } else {
            // Get attribute
            $product = Pi::api('attribute', 'shop')->Form($product);
            // Set data 
            $form->setData($product);
        }
        // Set view
        $this->view()->setTemplate('product_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Manage additional information'));

    }

    /* public function deleteAction()
    {
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $module = $this->params('module');
        $row = $this->getModel('product')->find($id);
        if ($row) {
            // Link
            $this->getModel('link')->delete(array('product' => $row->id));
            // Related
            $this->getModel('related')->delete(array('product_id' => $row->id));
            // Attach
            $this->getModel('attach')->delete(array('product' => $row->id));
            // attribute Field Data
            $this->getModel('field_data')->delete(array('product' => $row->id));
            // Special
            $this->getModel('special')->delete(array('product' => $row->id));
            // Remove sitemap
            if (Pi::service('module')->isActive('sitemap')) {
                $loc = Pi::url($this->url('shop', array(
                        'module'      => $module, 
                        'controller'  => 'product', 
                        'slug'        => $row->slug
                    )));
                Pi::api('sitemap', 'sitemap')->remove($loc);
            }
            // Add log
            Pi::api('log', 'shop')->addLog('product', $row->id, 'delete');
            // Remove page
            $row->delete();
            $this->jump(array('action' => 'index'), __('This product deleted'));
        } else {
            $this->jump(array('action' => 'index'), __('Please select product'));
        }
    } */

    public function recommendAction()
    {
        // Get id and recommended
        $id = $this->params('id');
        $recommended = $this->params('recommended');
        $return = array();
        // set product
        $product = $this->getModel('product')->find($id);
        // Check
        if ($product && in_array($recommended, array(0, 1))) {
            // Accept
            $product->recommended = $recommended;
            // Save
            if ($product->save()) {
                $return['message'] = sprintf(__('%s set recommended successfully'), $product->title);
                $return['ajaxstatus'] = 1;
                $return['id'] = $product->id;
                $return['recommended'] = $product->recommended;
                // Add log
                Pi::api('log', 'shop')->addLog('product', $product->id, 'recommend');
            } else {
                $return['message'] = sprintf(__('Error in set recommended for %s product'), $product->title);
                $return['ajaxstatus'] = 0;
                $return['id'] = 0;
                $return['recommended'] = $product->recommended;
            }
        } else {
            $return['message'] = __('Please select product');
            $return['ajaxstatus'] = 0;
            $return['id'] = 0;
            $return['recommended'] = 0;
        }
        return $return;
    }

    /**
     * related Action
     */
    public function relatedAction()
    {
    	// Get id
        $id = $this->params('id');
        $module = $this->params('module');
        $related_list = array();
        $product_list = array();
        // Find Product
        if ($id) {
        	$product = $this->getModel('product')->find($id)->toArray();
        } else {
        	return $this->redirect()->toRoute('', array('action' => 'index'));
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
            	$values = $form->getData();
            	$product_list = Pi::api('related', 'shop')->findList($product['id'], $values);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        }
        // Set view
    	$this->view()->setTemplate('product_related');
    	$this->view()->assign('title', __('Add Related'));
    	$this->view()->assign('form', $form);
    	$this->view()->assign('product', $product);
    	$this->view()->assign('related_list', $related_list);
    	$this->view()->assign('product_list', $product_list);
    }

    public function relatedAjaxAction()
    {
        // Get id and related
        $product_id = $this->params('product_id');
        $product_related = $this->params('product_related');
        $related = $this->params('related');
        $row = array();
        // Set return
        $return = array();
        $return['message'] = __('Please select product');
        $return['ajaxstatus'] = 0;
        $return['id'] = 0;
        $return['storystatus'] = 0;
        // set story
        $product = $this->getModel('product')->find($product_id);
        // Check product
        if ($product && in_array($related, array(0, 1))) {
        	// add / remove related
        	if ($related == 1) {
                if ($product['id'] != $product_related) {
                    // check related
                    $where = array('product_id' => $product['id'], 'product_related' => $product_related);
                    $select = $this->getModel('related')->select()->where($where)->limit(1);
                    $rowset = $this->getModel('related')->selectWith($select);
                    if ($rowset) {
                        $row = $rowset->toArray();
                    }
                    // Add related
                    if (empty($row)) {
                        // save
                        $row = $this->getModel('related')->createRow();
                        $row->product_id = $product['id'];
                        $row->product_related = $product_related;
                        $row->save();
                        // set return
                        $return['message'] = __('OK Add');
                        $return['ajaxstatus'] = 1;
                        $return['id'] = $product['id'];
                        $return['relatedstatus'] = 1;
                    } else {
                        // set return
                        $return['message'] = __('Error Add , It added before');
                        $return['ajaxstatus'] = 0;
                        $return['id'] = $product['id'];
                        $return['relatedstatus'] = 0;
                    }
                } else {
                    // set return
                    $return['message'] = __('Error , Product and related is same');
                    $return['ajaxstatus'] = 0;
                    $return['id'] = $product['id'];
                    $return['relatedstatus'] = 0;
                }
        	} elseif ($related == 0) {
        		$this->getModel('related')->delete(array('product_id' => $product['id'], 'product_related' => $product_related));
                $return['message'] = __('OK Remove');
                $return['ajaxstatus'] = 1;
                $return['id'] = $product['id'];
                $return['relatedstatus'] = 1;
        	}
            // update related count
            Pi::api('product', 'shop')->relatedCount($product['id']);
        }
        return $return;
    }

    public function removeAction()
    {
        // Get id and status
        $id = $this->params('id');
        // set product
        $product = $this->getModel('product')->find($id);
        // Check
        if ($product && !empty($id)) {
            // remove file
            /* $files = array(
                Pi::path(sprintf('upload/%s/original/%s/%s', $this->config('image_path'), $product->path, $product->image)),
                Pi::path(sprintf('upload/%s/large/%s/%s', $this->config('image_path'), $product->path, $product->image)),
                Pi::path(sprintf('upload/%s/medium/%s/%s', $this->config('image_path'), $product->path, $product->image)),
                Pi::path(sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $product->path, $product->image)),
            );
            Pi::service('file')->remove($files); */
            // clear DB
            $product->image = '';
            $product->path = '';
            // Save
            if ($product->save()) {
                $message = sprintf(__('Image of %s removed'), $product->title);
                $status = 1;
            } else {
                $message = __('Image not remove');
                $status = 0;
            }
        } else {
            $message = __('Please select product');
            $status = 0;
        }
        return array(
            'status' => $status,
            'message' => $message,
        );
    }
}