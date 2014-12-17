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
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Pi\File\Transfer\Upload;
use Module\Shop\Form\ProductForm;
use Module\Shop\Form\ProductFilter;
use Module\Shop\Form\RelatedForm;
use Module\Shop\Form\RelatedFilter;
use Module\Shop\Form\ExtraForm;
use Module\Shop\Form\ExtraFilter;
use Module\Shop\Form\SpecialForm;
use Module\Shop\Form\SpecialFilter;
use Zend\Json\Json;

class ProductController extends ActionController
{
    /**
     * Product Image Prefix
     */
    protected $ImageProductPrefix = 'product_';

    /**
     * Extra Image Prefix
     */
    protected $ImageExtraPrefix = 'extra_';

    /**
     * Product Columns
     */
    protected $productColumns = array(
    	'id', 'title', 'slug', 'category', 'summary', 'description', 'seo_title', 
        'seo_keywords', 'seo_description', 'status', 'time_create', 'time_update', 
        'uid', 'hits', 'sales', 'image', 'path', 'comment', 'point', 'count', 
        'favorite', 'attach', 'extra', 'related', 'recommended', 
        'stock', 'stock_alert', 'price', 'price_discount', 'price_title'
    );

    /**
     * Extra Columns
     */
    protected $extraColumns = array(
        'id', 'title', 'image', 'type', 'order', 'status', 'search', 'value'
    );

    /**
     * Special Columns
     */
    protected $specialColumns = array(
        'id', 'product', 'price', 'time_publish', 'time_expire', 'status'
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
        // Set info
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $order = array('time_create DESC', 'id DESC');
        $limit = intval($this->config('admin_perpage'));
        $list = array();
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
        $columnProduct = array('id', 'title', 'slug', 'status', 'time_create', 'recommended');
        $whereProduct = array('id' => $productId);
        // Get list of product
        $select = $this->getModel('product')->select()->columns($columnProduct)->where($whereProduct)->order($order);
        $rowset = $this->getModel('product')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $product[$row->id] = $row->toArray();
            $product[$row->id]['time_create_view'] = _date($product[$row->id]['time_create']);
            $product[$row->id]['time_update_view'] = _date($product[$row->id]['time_update']);
            $product[$row->id]['productUrl'] = $this->url('shop', array(
                'module'        => $module,
                'controller'    => 'product',
                'slug'          => $product[$row->id]['slug'],
            ));
        }
        // Go to update page if empty
        if (empty($product) && empty($status)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set paginator
        $countLink = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(DISTINCT `product`)'));
        $select = $this->getModel('link')->select()->where($whereLink)->columns($countLink);
        $count = $this->getModel('link')->selectWith($select)->current()->count;
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
            )),
        ));
        // Set view
        $this->view()->setTemplate('product_index');
        $this->view()->assign('list', $product);
        $this->view()->assign('paginator', $paginator);
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
        // Get extra field
        $fields = Pi::api('extra', 'shop')->Get();
        $option['field'] = $fields['extra'];
        // Set form
        $form = new ProductForm('product', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $file = $this->request->getFiles();
            // Set slug
            $slug = ($data['slug']) ? $data['slug'] : $data['title'];
            $data['slug'] = Pi::api('text', 'shop')->slug($slug);
            // Form filter
            $form->setInputFilter(new ProductFilter($fields['extra']));
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
                // Set extra data array
                if (!empty($fields['field'])) {
                    foreach ($fields['field'] as $field) {
                        $extra[$field]['field'] = $field;
                        $extra[$field]['data'] = $values[$field];
                    }
                }
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
                    $imageName = Pi::api('image', 'shop')->rename($file['image']['name'], $this->ImageProductPrefix);
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
                $values['seo_title'] = Pi::api('text', 'shop')->title($title);
                // Set seo_keywords
                $keywords = ($values['seo_keywords']) ? $values['seo_keywords'] : $values['title'];
                $values['seo_keywords'] = Pi::api('text', 'shop')->keywords($keywords);
                // Set seo_description
                $description = ($values['seo_description']) ? $values['seo_description'] : $values['title'];
                $values['seo_description'] = Pi::api('text', 'shop')->description($description);
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
                // Extra
                if (!empty($extra)) {
                    Pi::api('extra', 'shop')->Set($extra, $row->id);
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
                if ($row->id) {
                    $message = __('Product data saved successfully.');
                    $this->jump(array('action' => 'index'), $message);
                } else {
                    $message = __('Product data not saved.');
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        } else {
            if ($id) {
                // Get Extra
                $product = Pi::api('extra', 'shop')->Form($product);
                // Get tag list
                if (Pi::service('module')->isActive('tag')) {
                    $tag = Pi::service('tag')->get($module, $product['id'], '');
                    if (is_array($tag)) {
                        $product['tag'] = implode('|', $tag);
                    }
                }
                // Set data 
                $form->setData($product);
                $message = 'You can edit this product';
            } else {
                $message = 'You can add new product';
            }
        }   
        // Set view
        $this->view()->setTemplate('product_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add product'));
        $this->view()->assign('message', $message);
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
            // Extra Field Data
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

    /**
     * attribute Action
     */
    public function attributeAction()
    {
        $this->view()->setTemplate('product_attribute');
        $this->view()->assign('title', __('Add Attribute'));
        $this->view()->assign('message', __('This option ready on next version'));
    }

    /**
     * extra Action
     */
    public function extraAction()
    {
        // Get info
        $select = $this->getModel('field')->select()->order(array('order ASC'));
        $rowset = $this->getModel('field')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $field[$row->id] = $row->toArray();
            $field[$row->id]['imageUrl'] = Pi::url(sprintf('upload/%s/icon/%s', $this->config('file_path'), $field[$row->id]['image']));
        }
        // Go to update page if empty
        if (empty($field)) {
            return $this->redirect()->toRoute('', array('action' => 'extraUpdate'));
        }
        // Set view
        $this->view()->setTemplate('product_extra');
        $this->view()->assign('fields', $field);
    }

    /**
     * extra Action
     */
    public function extraUpdateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Get extra
        if ($id) {
            $extra = $this->getModel('field')->find($id)->toArray();
        }
        // Set form
        $form = new ExtraForm('extra', $options);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $file = $this->request->getFiles();
            $form->setInputFilter(new ExtraFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // upload image
                if (!empty($file['image']['name'])) {
                    // Set upload path
                    $path = Pi::path(sprintf('upload/%s/icon', $this->config('file_path')));
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($path);
                    $uploader->setRename($this->ImageExtraPrefix . '%random%');
                    $uploader->setExtension($this->config('image_extension'));
                    $uploader->setSize($this->config('image_size'));
                    if ($uploader->isValid()) {
                        $uploader->receive();
                        // Get image name
                        $values['image'] = $uploader->getUploaded('image');
                    } else {
                        $this->jump(array('action' => 'update'), __('Problem in upload image. please try again'));
                    }
                } elseif (!isset($values['image'])) {
                    $values['image'] = '';  
                }
                // Set just product fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->extraColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set order
                $select = $this->getModel('field')->select()->columns(array('order'))->order(array('order DESC'))->limit(1);
                $values['order'] = $this->getModel('field')->selectWith($select)->current()->order + 1;
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('field')->find($values['id']);
                } else {
                    $row = $this->getModel('field')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('extra', $row->id, $operation);
                // Check it save or not
                if ($row->id) {
                    $message = __('Extra field data saved successfully.');
                    $url = array('action' => 'extra');
                    $this->jump($url, $message);
                } else {
                    $message = __('Extra field data not saved.');
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        } else {
            if ($id) {
                $form->setData($extra);
                $message = 'You can edit this extra field';
            } else {
                $message = 'You can add new extra field';
            }
        }
        // Set view
        $this->view()->setTemplate('product_extra_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add Extra'));
        $this->view()->assign('message', $message);
    }

    public function extraSortAction()
    {
        $order = 1;
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            foreach ($data['mod'] as $id) {
                if ($id > 0) {
                    $row = $this->getModel('field')->find($id);
                    $row->order = $order;
                    $row->save();
                    $order++;
                }
            }
        }
        // Set view
        $this->view()->setTemplate(false);
    }

    public function extraDeleteAction()
    {
        // Get information
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('field')->find($id);
        if ($row) {
            // Remove all data
            $this->getModel('field_data')->delete(array('field' => $row->id));
            // Remove field
            $row->delete();
            $this->jump(array('action' => 'extra'), __('Selected field delete'));
        } else {
            $this->jump(array('action' => 'extra'), __('Please select field'));
        }
    }

    public function specialAction()
    {
        // Get product and category
        $where = array('time_expire > ?' => time());
        $columns = array('product');
        $select = $this->getModel('special')->select()->where($where)->columns($columns);
        $idSet = $this->getModel('special')->selectWith($select)->toArray();
        if (empty($idSet)) {
            return $this->redirect()->toRoute('', array('action' => 'specialUpdate'));
        }
        // Set topics and stores
        foreach ($idSet as $special) {
            $productArr[] = $special['product'];
        }
        // Get products
        $where = array('id' => array_unique($productArr));
        $columns = array('id', 'title', 'slug');
        $select = $this->getModel('product')->select()->where($where)->columns($columns);
        $productSet = $this->getModel('product')->selectWith($select);
        // Make product list
        foreach ($productSet as $row) {
            $productList[$row->id] = $row->toArray();
        }
        // Get special
        $where = array('time_expire > ?' => time());
        $order = array('id DESC', 'time_publish DESC');
        $select = $this->getModel('special')->select()->where($where)->order($order);
        $specialSet = $this->getModel('special')->selectWith($select);
        // Make special list
        foreach ($specialSet as $row) {
            $specialList[$row->id] = $row->toArray();
            $specialList[$row->id]['productTitle'] = $productList[$row->product]['title'];
            $specialList[$row->id]['productSlug'] = $productList[$row->product]['slug'];
            $specialList[$row->id]['time_publish'] = _date($specialList[$row->id]['time_publish']);
            $specialList[$row->id]['time_expire'] = _date($specialList[$row->id]['time_expire']);
        }
        // Set view
        $this->view()->setTemplate('product_special');
        $this->view()->assign('specials', $specialList);
    }

    public function specialUpdateAction()
    {
        // Get id
        $id = $this->params('id');
        $form = new SpecialForm('special');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new SpecialFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->specialColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set time
                $values['time_publish'] = strtotime($values['time_publish']);
                $values['time_expire'] = strtotime($values['time_expire']);
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('special')->find($values['id']);
                } else {
                    $row = $this->getModel('special')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('special', $row->id, $operation);
                // Check it save or not
                if ($row->id) {
                    $message = __('Special data saved successfully.');
                    $this->jump(array('action' => 'special'), $message);
                } else {
                    $message = __('Special data not saved.');
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        } else {
            if ($id) {
                $values = $this->getModel('special')->find($id)->toArray();
                $form->setData($values);
                $message = 'You can edit this special';
            } else {
                $message = 'You can add new special';
            }
        }
        $this->view()->setTemplate('product_special_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add Special'));
        $this->view()->assign('message', $message);
    }

    public function specialDeleteAction()
    {
        // Get information
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('special')->find($id);
        if ($row) {
            $row->delete();
            $this->jump(array('action' => 'special'), __('Selected special delete'));
        }
        $this->jump(array('action' => 'special'), __('Please select special'));
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