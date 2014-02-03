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
        $list = array();
        return array(
            'options'   => $options,
            'list'      => $list
        );
    }

    public static function productRandom($options = array(), $module = null)
    {
        $list = array();
        return array(
            'options'   => $options,
            'list'      => $list
        );
    }

    public static function category($options = array(), $module = null)
    {
        $list = array();
        $columns = array('id', 'parent', 'title', 'slug');
        $where = array('status' => 1);
        $select = Pi::model('category', $module)->select()->columns($columns)->where($where);
        $rowset = Pi::model('category', $module)->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
            $list[$row->id]['url'] = Pi::service('url')->assemble('discount', array(
                'module'        => $module,
                'controller'    => 'category',
                'slug'          => $list[$row->id]['slug'],
            ));
        }
        // Set return
        return array(
            'options'   => $options,
            'list'      => $list
        );
    }
}	