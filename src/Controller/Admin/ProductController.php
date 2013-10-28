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
        // Set form
        $form = new ProductForm('product');
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
                // Topic
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
}