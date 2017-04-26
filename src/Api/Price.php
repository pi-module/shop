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
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('price', 'shop')->addLog($price, $product, $type, $extra);
 * Pi::api('price', 'shop')->lastUpdate($product, $type);
 */

class Price extends AbstractApi
{
    public function addLog($price, $product, $type = 'product', $extra = '')
    {
        $row = Pi::model('price', $this->getModule())->createRow();
        $row->uid = Pi::user()->getId();
        $row->time_update = time();

        $row->price = $price;
        $row->type = $type;
        $row->product = $product;

        if (!empty($extra)) {
            switch ($type) {
                case 'property':
                    $row->property = $extra;
                    break;

                case 'sale':
                    $row->sale = $extra;
                    break;
            }
        }

        $row->save();
    }

    public function lastUpdate($product, $type = '')
    {
        $price = array();

        // Set where
        $order = array('time_update DESC', 'id DESC');
        $where = array('product' => $product);
        if (!empty($type) && in_array($type, array('product', 'property', 'sale'))) {
            $where['type'] = $type;
        }

        // Select
        $select = Pi::model('price', $this->getModule())->select()->where($where)->order($order)->limit(1);
        $row = Pi::model('price', $this->getModule())->selectWith($select)->current();

        // Get time update
        if (empty($row)) {
            $price = $row->toArray();
        }

        return $price;
    }
}