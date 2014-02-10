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
namespace Module\Shop\Block;

use Pi;

class Block
{
    public static function productNew($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        // Set info
        $where = array('status' => 1);
        $order = array('time_create DESC', 'id DESC');
        $limit = intval($block['number']);
        // Get category list
        $categoryList = Pi::api('category', 'shop')->categoryList();
        // Get list of product
        $select = Pi::model('product', $module)->select()->where($where)->order($order)->limit($limit);
        $rowset = Pi::model('product', $module)->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row, $categoryList);
        }
        // Set block array
        $block['resources'] = $product;
        return $block;
    }

    public static function productRandom($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        // Set info
        $where = array('status' => 1);
        $order = array(new \Zend\Db\Sql\Predicate\Expression('RAND()'));
        $limit = intval($block['number']);
        // Get category list
        $categoryList = Pi::api('category', 'shop')->categoryList();
        // Get list of product
        $select = Pi::model('product', $module)->select()->where($where)->order($order)->limit($limit);
        $rowset = Pi::model('product', $module)->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row, $categoryList);
        }
        // Set block array
        $block['resources'] = $product;
        return $block;
    }

    public static function category($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Set info
        $columns = array('id', 'parent', 'title', 'slug');
        $where = array('status' => 1);
        $select = Pi::model('category', $module)->select()->columns($columns)->where($where);
        $rowset = Pi::model('category', $module)->selectWith($select);
        foreach ($rowset as $row) {
            $category[$row->id] = $row->toArray();
            $category[$row->id]['url'] = Pi::service('url')->assemble('shop', array(
                'module'        => $module,
                'controller'    => 'category',
                'slug'          => $category[$row->id]['slug'],
            ));
        }
        // Set block array
        $block['resources'] = $category;
        return $block;
    }
}	