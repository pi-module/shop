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
use Module\Shop\Form\ProductForm;
use Module\Shop\Form\ProductFilter;
use Module\Shop\Form\RelatedForm;
use Module\Shop\Form\RelatedFilter;
use Module\Shop\Form\PropertyForm;
use Module\Shop\Form\PropertyFilter;
use Zend\Json\Json;

class ProductController extends ActionController
{
    /**
     * Image Prefix
     */
    protected $ImagePrefix = 'product_';

    /**
     * Product Columns
     */
    protected $productColumns = array(
    	'id', 'title', 'slug', 'category', 'related', 'summary', 'description', 'seo_title', 'seo_keywords',
    	'seo_description', 'status', 'time_create', 'time_update', 'uid', 'hits', 'image', 'path', 'comment',
    	'point', 'count', 'favorite', 'attach', 'extra', 'recommended', 'stock', 'stock_alert', 'price', 
    	'price_discount', 'property_1', 'property_2', 'property_3', 'property_4', 'property_5', 'property_6',
    	'property_7', 'property_8', 'property_9', 'property_10', 
    );

    /**
     * index Action
     */
	public function indexAction()
    {
        // Get page
        $page = $this->params('p', 1);
        $module = $this->params('module');
        $list = array();
        // Get info
        $columns = array('id', 'title', 'slug', 'status');
        $order = array('id DESC', 'time_create DESC');
        $select = $this->getModel('product')->select()->columns($columns)->order($order);
        $rowset = $this->getModel('product')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        // Go to update page if empty
        if (empty($list)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set view
        $this->view()->setTemplate('product_index');
        $this->view()->assign('list', $list);
    }

    /**
     * update Action
     */
    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Find Product
        if ($id) {
            $product = $this->getModel('product')->find($id)->toArray();
            $product['category'] = Json::decode($product['category']);
        }
        // Set property
        $where = array('module' => $module, 'category' => 'property');
        $order = array('order ASC');
        $select = Pi::model('config')->select()->where($where)->order($order);
        $rowset = Pi::model('config')->selectWith($select);
        $configs = array();
        foreach ($rowset as $row) {
            $property[$row->name] = $row->toArray();
        }
        // Set form
        $form = new ProductForm('product', $property);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $file = $this->request->getFiles();
            // Set slug
            $slug = ($data['slug']) ? $data['slug'] : $data['title'];
            $data['slug'] = Pi::api('shop', 'text')->slug($slug);
            // Form filter
            $form->setInputFilter(new ProductFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	//
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->productColumns)) {
                        unset($values[$key]);
                    }
                }
                // Category
                $values['category'] = Json::encode(array_unique($values['category']));
                // Set seo_title
                $title = ($values['seo_title']) ? $values['seo_title'] : $values['title'];
                $values['seo_title'] = Pi::api('shop', 'text')->title($title);
                // Set seo_keywords
                $keywords = ($values['seo_keywords']) ? $values['seo_keywords'] : $values['title'];
                $values['seo_keywords'] = Pi::api('shop', 'text')->keywords($keywords);
                // Set seo_description
                $description = ($values['seo_description']) ? $values['seo_description'] : $values['title'];
                $values['seo_description'] = Pi::api('shop', 'text')->description($description);
                // Set time
                if (empty($values['id'])) {
                    $values['time_create'] = time();
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
                Pi::api('shop', 'category')->setLink($row->id, $row->category, $row->time_create, $row->time_update, $row->price, $row->stock, $row->status);
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
                $form->setData($product);
                $message = 'You can edit this product';
            } else {
                $message = 'You can add new product';
            }
        }   
        // Set view
        $this->view()->setTemplate('product_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a product'));
        $this->view()->assign('message', $message);
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
    	$related_list = Pi::api('shop', 'related')->getListAll($product['id']);
    	// Set form
        $form = new RelatedForm('related');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $form->setInputFilter(new RelatedFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	$product_list = Pi::api('shop', 'related')->findList($product['id'], $values);
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
        	} elseif ($related == 0) {
        		$this->getModel('related')->delete(array('product_id' => $product['id'], 'product_related' => $product_related));
                $return['message'] = __('OK Remove');
                $return['ajaxstatus'] = 1;
                $return['id'] = $product['id'];
                $return['relatedstatus'] = 1;
        	}
        }
        return $return;
    }

    /**
     * attach Action
     */
    public function attachAction()
    {
    	$this->view()->setTemplate('product_attach');
    }	

    /**
     * attribute Action
     */
    public function attributeAction()
    {
        $this->view()->setTemplate('product_attribute');
    }

    /**
     * property Action
     */
    public function propertyAction()
    {
        $module = $this->params('module');
        $where = array('module' => $module, 'category' => 'property');
        $order = array('order ASC');
        $select = Pi::model('config')->select()->where($where)->order($order);
        $rowset = Pi::model('config')->selectWith($select);
        $configs = array();
        foreach ($rowset as $row) {
            $configs[] = $row;
            $items[$row->id] = $row->toArray();
        }
        // Set form
        $form = new PropertyForm('property', $items);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                unset($values['submit']);
                foreach ($configs as $row) {
                    $row->value = $values[$row->name];
                    $row->save();
                }
                $message = __('Property data saved successfully.');
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }   
        } else {
            $message = __('you can update property');
        }
        // Set view
        $this->view()->setTemplate('product_property');
        $this->view()->assign('form', $form);
        $this->view()->assign('message', $message);
    }

    /**
     * extra Action
     */
    public function extraAction()
    {
        $this->view()->setTemplate('product_extra');
    }
}