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
        $product = array();
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
        $product = array();
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
            }
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
        if ($block['type'] == 'advanced') {
            $block['tree'] = self::getTree($category);
            $block['treeHtml'] = self::getTreeHtml($block['tree']);
        }
        return $block;
    }

    public static function getTree($elements, $parentId = 0)
    {
        $category = array();
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $depth = 0;
                $category[$element['id']]['id'] = $element['id'];
                $category[$element['id']]['title'] = $element['title'];
                $category[$element['id']]['url'] = $element['url'];
                $children = self::getTree($elements, $element['id']);
                if ($children) {
                    $depth++;
                    foreach ($children as $key => $value) {
                        $category[$element['id']]['children'][$key] = array(
                            'id' => $value['id'],
                            'title' => $value['title'],
                            'url' => $value['url'],
                            'children' => empty($value['children']) ? '' : $value['children'],
                        );
                    }
                }       
                unset($elements[$element['id']]);
                unset($depth);            
            }
        }
        return $category;
    }

    public static function getTreeHtml($list)
    {
        $html = '<ul class="nav nav-pills">' . PHP_EOL;
        foreach ($list as $sub) {
            $html .= '<li>' . PHP_EOL;
            $html .= '<a title="' . $sub['title'] . '" href="' . $sub['url'] . '">' . $sub['title'] . '</a>' . PHP_EOL;
            if (!empty($sub['children'])) {
                $html .= self::getTreeHtml($sub['children']);
            }
            $html .= '</li>' . PHP_EOL;
        }
        $html .= '</ul>' . PHP_EOL;
        return $html;
    }
}