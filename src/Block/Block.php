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
use Zend\Db\Sql\Predicate\Expression;

class Block
{
    public static function productNew($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        $product = array();
        // Set info
        $order = array('time_create DESC', 'id DESC');
        $limit = intval($block['number']);
        if (isset($block['category']) &&
            !empty($block['category']) &&
            !in_array(0, $block['category'])
        ) {
            // Set info
            $where = array(
                'status' => 1,
                'category' => $block['category'],
            );
            // Set info
            $columns = array('product' => new Expression('DISTINCT product'));
            // Get info from link table
            $select = Pi::model('link', $module)->select()->where($where)->columns($columns)->order($order)->limit($limit);
            $rowset = Pi::model('link', $module)->selectWith($select)->toArray();
            // Make list
            foreach ($rowset as $id) {
                $productId[] = $id['product'];
            }
            // Set info
            $where = array('status' => 1, 'id' => $productId);
        } else {
            $where = array('status' => 1);
        }
        // Get list of product
        $select = Pi::model('product', $module)->select()->where($where)->order($order)->limit($limit);
        $rowset = Pi::model('product', $module)->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row);
        }
        // Set block array
        $block['resources'] = $product;
        // Load language
        Pi::service('i18n')->load(array('module/shop', 'default'));
        return $block;
    }

    public static function productRandom($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        $product = array();
        // Set info
        $order = array(new Expression('RAND()'));
        $limit = intval($block['number']);
        if (isset($block['category']) &&
            !empty($block['category']) &&
            !in_array(0, $block['category'])
        ) {
            // Set info
            $where = array(
                'status' => 1,
                'category' => $block['category'],
            );
            // Set info
            $columns = array('product' => new Expression('DISTINCT product'));
            // Get info from link table
            $select = Pi::model('link', $module)->select()->where($where)->columns($columns)->order($order)->limit($limit);
            $rowset = Pi::model('link', $module)->selectWith($select)->toArray();
            // Make list
            foreach ($rowset as $id) {
                $productId[] = $id['product'];
            }
            // Set info
            $where = array('status' => 1, 'id' => $productId);
        } else {
            $where = array('status' => 1);
        }
        // Get list of product
        $select = Pi::model('product', $module)->select()->where($where)->order($order)->limit($limit);
        $rowset = Pi::model('product', $module)->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row);
        }
        // Set block array
        $block['resources'] = $product;
        // Load language
        Pi::service('i18n')->load(array('module/shop', 'default'));
        return $block;
    }

    public static function productTag($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        $product = array();
        // Check tag term
        if (!empty($block['tag-term'])) {
            // Get product ides from tag term
            $tags = Pi::service('tag')->getList($block['tag-term'], 'shop');
            foreach ($tags as $tag) {
                $tagId[] = $tag['item'];
            }
            // get products
            if (!empty($tagId)) {
                // Set info
                $where = array('status' => 1, 'id' => $tagId);
                $order = array(new Expression('RAND()'));
                $limit = intval($block['number']);
                // Get list of product
                $select = Pi::model('product', $module)->select()->where($where)->order($order)->limit($limit);
                $rowset = Pi::model('product', $module)->selectWith($select);
                // Make list
                foreach ($rowset as $row) {
                    $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row);
                }
            }
        }
        // Set block array
        $block['resources'] = $product;
        // Load language
        Pi::service('i18n')->load(array('module/shop', 'default'));
        return $block;
    }

    public function productSale($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        $limit = intval($block['number']);
        // Set block array
        $block['resources'] = Pi::api('sale', 'shop')->getAll($limit);
        // Load language
        Pi::service('i18n')->load(array('module/shop', 'default'));
        return $block;
    }

    public static function category($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Set info
        $order = array('display_order DESC', 'id DESC');
        $where = array('status' => 1);
        if (!empty($block['category']) && !in_array(0, $block['category'])) {
            $where['id'] = $block['category'];
        }
        // Select
        $select = Pi::model('category', $module)->select()->where($where)->order($order);
        $rowset = Pi::model('category', $module)->selectWith($select);
        foreach ($rowset as $row) {
            $category[$row->id] = Pi::api('category', 'shop')->canonizeCategory($row);
        }
        // Set block array
        $block['resources'] = $category;
        return $block;
    }

    public static function basket($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Set basket link
        $block['link'] = Pi::url(Pi::service('url')->assemble('shop', array(
            'module' => $module,
            'controller' => 'cart',
            'action' => 'index',
        )));
        // Check block type
        switch($block['type']) {
            case 'link':
                // Set number
                $block['number'] = Pi::api('basket', 'shop')->basketBlockNumber();
                $block['number_view'] = _number($block['number']);
                break;

            case 'dialog':
                $info = Pi::api('basket', 'shop')->basketBlockInfo();
                $block['list'] = $info['list'];
                $block['number'] = $info['number'];
                $block['number_view'] = _number($block['number']);
                break;
        }
        // Set block array
        return $block;
    }

    public static function search($options = array(), $module = null)
    {
        // Set options
        $block = array();
        $block = array_merge($block, $options);
        // Set ajax search link
        $block['link'] = Pi::url(Pi::service('url')->assemble('shop', array(
            'module' => $module,
            'controller' => 'json',
            'action' => 'filterSearch',
        )));
        // Set block array
        return $block;
    }
}