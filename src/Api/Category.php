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
 * Pi::api('shop', 'category')->setLink($product, $category, $create, $update, $price, $stock, $status);
 * Pi::api('shop', 'category')->findFromCategory($category);
 * Pi::api('shop', 'category')->categoryList($parent);
 */

class Category extends AbstractApi
{
    /**
     * Set product category to link table
     */
    public function setLink($product, $category, $create, $update, $price, $stock, $status)
    {
        //Remove
        Pi::model('link', $this->getModule())->delete(array('product' => $product));
        // Add
        $allCategory = Json::decode($category);
        foreach ($allCategory as $category) {
            // Set array
            $values['product'] = $product;
            $values['category'] = $category;
            $values['time_create'] = $create;
            $values['time_update'] = $update;
            $values['price'] = $price;
            $values['stock'] = ($stock > 0) ? 1 : 0;
            $values['status'] = $status;
            // Save
            $row = Pi::model('link', $this->getModule())->createRow();
            $row->assign($values);
            $row->save();
        }
    }

    public function findFromCategory($category)
    {
        $list = array();
        $where = array('category' => $category);
        $select = Pi::model('link', $this->getModule())->select()->where($where);
        $rowset = Pi::model('link', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $row = $row->toArray();
            $list[] = $row['product'];
        }
        return array_unique($list);
    }

    public function categoryList($parent = null)
    {
        $return = array();
        if (isset($_SESSION['shop']['category']) 
            && !empty($_SESSION['shop']['category'])
            && $_SESSION['shop']['parent'] == $parent) 
        {
            $return = $_SESSION['shop']['category'];
        } else {
        
            $where = array('status' => 1);
            $order = array('time_create DESC', 'id DESC');
            if (!is_null($parent)) {
                $where['parent'] = $parent;
            }
            $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
            $rowset = Pi::model('category', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                $return[$row->id] = $row->toArray();
                $return[$row->id]['url'] = Pi::service('url')->assemble('shop', array(
                    'module'        => $this->getModule(),
                    'controller'    => 'category',
                    'slug'          => $return[$row->id]['slug'],
                ));
            }
            $_SESSION['shop']['category'] = $return;
            $_SESSION['shop']['parent'] == $parent;
        }
        return $return;
    }  
}