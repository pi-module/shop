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
 * Pi::api('shop', 'product')->getProduct($parameter, $type);
 * Pi::api('shop', 'product')->getListFromId($id);
 * Pi::api('shop', 'product')->searchRelated($title, $type);
 * Pi::api('shop', 'product')->extraCount($id);
 * Pi::api('shop', 'product')->attachCount($id);
 * Pi::api('shop', 'product')->relatedCount($id);
 * Pi::api('shop', 'product')->reviewCount($id);
 * Pi::api('shop', 'product')->AttachList($id);
 * Pi::api('shop', 'product')->viewPrice($price);
 * Pi::api('shop', 'product')->canonizeProduct($product, $categoryList);
 */

class Product extends AbstractApi
{
    public function getProduct($parameter, $type = 'id')
    {
        // Get category list
        $categoryList = Pi::api('shop', 'category')->categoryList();
        // Get product
        $product = Pi::model('product', $this->getModule())->find($parameter, $type);
        $product = $this->canonizeProduct($product, $categoryList);
        return $product;
    }

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
    public function extraCount($id)
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
    public function attachCount($id)
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
     * Set number of attach files for selected product
     */
    public function relatedCount($id)
    {
        // Get attach count
        $where = array('product_id' => $id);
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('related', $this->getModule())->select()->columns($columns)->where($where);
        $count = Pi::model('related', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('product', $this->getModule())->update(array('related' => $count), array('id' => $id));
    }

    /**
     * Set number of reviews for selected product
     */
    public function reviewCount($id)
    {
        // Get attach count
        $where = array('product' => $id, 'status' => 1);
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('review', $this->getModule())->select()->columns($columns)->where($where);
        $count = Pi::model('review', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('product', $this->getModule())->update(array('review' => $count), array('id' => $id));
    }

    /**
     * Get list of attach files
     */
    public function AttachList($id)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Set info
        $file = array();
        $where = array('product' => $id, 'status' => 1);
        $order = array('time_create DESC', 'id DESC');
        // Get all attach files
        $select = Pi::model('attach', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('attach', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $file[$row->type][$row->id] = $row->toArray();
            $file[$row->type][$row->id]['time_create_view'] = _date($file[$row->type][$row->id]['time_create']);
            if ($file[$row->type][$row->id]['type'] == 'image') {
                // Set image original url
                $file[$row->type][$row->id]['originalUrl'] = Pi::url(
                    sprintf('upload/%s/original/%s/%s', 
                        $config['image_path'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
                // Set image large url
                $file[$row->type][$row->id]['largeUrl'] = Pi::url(
                    sprintf('upload/%s/large/%s/%s', 
                        $config['image_path'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
                // Set image medium url
                $file[$row->type][$row->id]['mediumUrl'] = Pi::url(
                    sprintf('upload/%s/medium/%s/%s', 
                        $config['image_path'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
                // Set image thumb url
                $file[$row->type][$row->id]['thumbUrl'] = Pi::url(
                    sprintf('upload/%s/thumb/%s/%s', 
                        $config['image_path'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
            } else {
                $file[$row->type][$row->id]['fileUrl'] = Pi::url(
                    sprintf('upload/%s/%s/%s/%s', 
                        $config['file_path'], 
                        $file[$row->type][$row->id]['type'], 
                        $file[$row->type][$row->id]['path'], 
                        $file[$row->type][$row->id]['file']
                    ));
            }
        }
        // return
        return $file;
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
        $product['category'] = Json::decode($product['category']);
        foreach ($product['category'] as $category) {
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