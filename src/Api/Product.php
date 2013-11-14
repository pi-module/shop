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
namespace Module\Shop\Api;

use Pi;
use Pi\Application\AbstractApi;
use Zend\Json\Json;

/*
 * Pi::api('shop', 'product')->getListFromId($id);
 * Pi::api('shop', 'product')->searchRelated($title, $type);
 * Pi::api('shop', 'product')->extraCount($id);
 * Pi::api('shop', 'product')->attachCount($id);
 * Pi::api('shop', 'product')->viewPrice($price);
 * Pi::api('shop', 'product')->canonizeProduct($product, $categoryList);
 */

class Product extends AbstractApi
{
    public function searchRelated($title, $type)
    {
    	$list = array();
    	switch ($type) {
    		case 1:
    		    $where = array('title LIKE ?' => '%' . $title . '%');
    			break;

    		case 2:
    		    $where = array('title LIKE ?' => $title . '%');
    			break;
    			
    		case 3:
    		    $where = array('title LIKE ?' => '%' . $title);
    			break;
    			
    		case 4:
    		    $where = array('title' => $title);
    			break;			
    	}
    	$columns = array('id');
    	$select = Pi::model('product', $this->getModule())->select()->where($where)->columns($columns);
    	$rowset = Pi::model('product', $this->getModule())->selectWith($select);
    	foreach ($rowset as $row) {
            $list[] = $row->id;
        }
        return $list;
    }

    public function getListFromId($id)
    {
    	$list = array();
    	$where = array('id' => $id, 'status' => 1);
    	$select = Pi::model('product', $this->getModule())->select()->where($where);
    	$rowset = Pi::model('product', $this->getModule())->selectWith($select);
    	foreach ($rowset as $row) {
            $list[$row->id] = $this->canonizeProduct($row);
        }
        return $list;
    }	

    /**
     * Set number of used extra fields for selected product
     */
    public function ExtraCount($id)
    {
        // Get attach count
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('field_data', $this->getModule())->select()->columns($columns);
        $count = Pi::model('field_data', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('product', $this->getModule())->update(array('extra' => $count), array('id' => $id));
    }

    /**
     * Set number of attach files for selected product
     */
    public function AttachCount($id)
    {
        // Get attach count
        $where = array('product' => $id);
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('attach', $this->getModule())->select()->columns($columns)->where($where);
        $count = Pi::model('attach', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('product', $this->getModule())->update(array('attach' => $count), array('id' => $id));
    }

    /**
     * Set product view price
     */
    public function viewPrice($price)
    {
        if ($price > 0) {
            $viewPrice = _currency($price);
        } else {
            $viewPrice = '';
        }
        return $viewPrice;

    }

    public function canonizeProduct($product, $categoryList = array())
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Get category list
        $categoryList = (empty($categoryList)) ? Pi::api('shop', 'category')->categoryList() : $categoryList;
        // boject to array
        $product = $product->toArray();
        // Set summary text
        $product['summary'] = Pi::service('markup')->render($product['summary'], 'text', 'html');
        // Set description text
        $product['description'] = Pi::service('markup')->render($product['description'], 'text', 'html');
        // Set times
        $product['time_create_view'] = _date($product['time_create']);
        $product['time_update_view'] = _date($product['time_update']);
        // Set product url
        $product['productUrl'] = Pi::service('url')->assemble('shop', array(
            'module'        => $this->getModule(),
            'controller'    => 'product',
            'slug'          => $product['slug'],
        ));
        // Set cart url
        $product['cartUrl'] = '#';
        // Set category information
        $productCategory = Json::decode($product['category']);
        foreach ($productCategory as $category) {
            $product['categories'][$category]['title'] = $categoryList[$category]['title'];
            $product['categories'][$category]['url'] = Pi::service('url')->assemble('shop', array(
                'module'        => $this->getModule(),
                'controller'    => 'category',
                'slug'          => $categoryList[$category]['slug'],
            ));
        }
        // Set price
        $product['price_view'] = $this->viewPrice($product['price']);
        $product['price_discount_view'] = $this->viewPrice($product['price_discount']);
        // Set image url
        if ($product['image']) {
            // Set image original url
            $product['originalUrl'] = Pi::url(
                sprintf('upload/%s/original/%s/%s', 
                    $config['image_path'], 
                    $product['path'], 
                    $product['image']
                ));
            // Set image large url
            $product['largeUrl'] = Pi::url(
                sprintf('upload/%s/large/%s/%s', 
                    $config['image_path'], 
                    $product['path'], 
                    $product['image']
                ));
            // Set image medium url
            $product['mediumUrl'] = Pi::url(
                sprintf('upload/%s/medium/%s/%s', 
                    $config['image_path'], 
                    $product['path'], 
                    $product['image']
                ));
            // Set image thumb url
            $product['thumbUrl'] = Pi::url(
                sprintf('upload/%s/thumb/%s/%s', 
                    $config['image_path'], 
                    $product['path'], 
                    $product['image']
                ));
        }
        // return product
        return $product; 
    }
}	