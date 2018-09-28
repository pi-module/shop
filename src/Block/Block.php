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

namespace Module\Shop\Block;

use Pi;
use Zend\Db\Sql\Predicate\Expression;

class Block
{
    public static function productNew($options = [], $module = null)
    {
        // Set options
        $block = [];
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        $product = [];
        // Set info
        $order = ['time_create DESC', 'id DESC'];
        $limit = intval($block['number']);
        if (isset($block['category']) &&
            !empty($block['category']) &&
            !in_array(0, $block['category'])
        ) {
            // Set info
            $where = [
                'status'   => 1,
                'category' => $block['category'],
            ];
            // Set info
            $columns = ['product' => new Expression('DISTINCT product')];
            // Get info from link table
            $select = Pi::model('link', $module)->select()->where($where)->columns($columns)->order($order)->limit($limit);
            $rowset = Pi::model('link', $module)->selectWith($select)->toArray();
            // Make list
            foreach ($rowset as $id) {
                $productId[] = $id['product'];
            }
            // Set info
            $where = ['status' => 1, 'id' => $productId];
        } else {
            $where = ['status' => 1];
        }
        if ($block['recommended']) {
            $where['recommended'] = 1;
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
        Pi::service('i18n')->load(['module/shop', 'default']);
        return $block;
    }

    public static function productRandom($options = [], $module = null)
    {
        // Set options
        $block = [];
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        $product = [];
        // Set info
        $order = [new Expression('RAND()')];
        $limit = intval($block['number']);
        if (isset($block['category']) &&
            !empty($block['category']) &&
            !in_array(0, $block['category'])
        ) {
            // Set info
            $where = [
                'status'   => 1,
                'category' => $block['category'],
            ];
            // Set info
            $columns = ['product' => new Expression('DISTINCT product')];
            // Get info from link table
            $select = Pi::model('link', $module)->select()->where($where)->columns($columns)->order($order)->limit($limit);
            $rowset = Pi::model('link', $module)->selectWith($select)->toArray();
            // Make list
            foreach ($rowset as $id) {
                $productId[] = $id['product'];
            }
            // Set info
            $where = ['status' => 1, 'id' => $productId];
        } else {
            $where = ['status' => 1];
        }
        if ($block['recommended']) {
            $where['recommended'] = 1;
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
        Pi::service('i18n')->load(['module/shop', 'default']);
        return $block;
    }

    public static function productTag($options = [], $module = null)
    {
        // Set options
        $block = [];
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        $product = [];
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
                $where = ['status' => 1, 'id' => $tagId];
                if ($block['recommended']) {
                    $where['recommended'] = 1;
                }
                $order = [new Expression('RAND()')];
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
        Pi::service('i18n')->load(['module/shop', 'default']);
        return $block;
    }

    public function productSale($options = [], $module = null)
    {
        // Set options
        $block = [];
        $block = array_merge($block, $options);
        $block['config'] = Pi::service('registry')->config->read('shop', 'order');
        $limit = intval($block['number']);
        // Set block array
        $block['resources'] = Pi::api('sale', 'shop')->getAll($limit, 'product');
        return $block;
    }

    public function categorySale($options = [], $module = null)
    {
        // Set options
        $block = [];
        $block = array_merge($block, $options);
        $limit = intval($block['number']);
        // Set block array
        $block['resources'] = Pi::api('sale', 'shop')->getAll($limit, 'category');
        return $block;
    }

    public static function category($options = [], $module = null)
    {
        // Set options
        $block = [];
        $block = array_merge($block, $options);
        // Set info
        $order = ['display_order DESC', 'id DESC'];
        $where = ['status' => 1];
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

    public static function basket($options = [], $module = null)
    {
        // Set options
        $block = [];
        $block = array_merge($block, $options);
        // Set basket link
        $block['link'] = Pi::url(Pi::service('url')->assemble('shop', [
            'module'     => $module,
            'controller' => 'cart',
            'action'     => 'index',
        ]));
        // Check block type
        switch ($block['type']) {
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

    public static function search($options = [], $module = null)
    {
        // Set options
        $block = [];
        $block = array_merge($block, $options);
        // Set ajax search link
        $block['link'] = Pi::url(Pi::service('url')->assemble('shop', [
            'module'     => $module,
            'controller' => 'json',
            'action'     => 'filterSearch',
        ]));
        // Set block array
        return $block;
    }
}