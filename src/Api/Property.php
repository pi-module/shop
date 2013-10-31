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

/*
 * Pi::api('shop', 'property')->Get();
 */

class Property extends AbstractApi
{
    /*
      * Get list of property fields for show in forms
      */
    public function Get()
    {
        // Get property
        $where = array('module' => $this->getModule(), 'category' => 'property');
        $order = array('order ASC');
        $property = array();
        $select = Pi::model('config')->select()->where($where)->order($order);
        $rowset = Pi::model('config')->selectWith($select);
        foreach ($rowset as $row) {
            $property[$row->name] = $row->toArray();
        }
        return $property;
    }	
}	