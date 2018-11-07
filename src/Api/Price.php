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

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('price', 'shop')->addLog($price, $product, $type, $extra);
 * Pi::api('price', 'shop')->lastUpdate($product, $type);
 * Pi::api('price', 'shop')->makeUpdatePrice($price, $percent);
 */

class Price extends AbstractApi
{
    public function addLog($price, $product, $type = 'product', $extra = '')
    {
        $row              = Pi::model('price', $this->getModule())->createRow();
        $row->uid         = Pi::user()->getId();
        $row->time_update = time();

        $row->price   = $price;
        $row->type    = $type;
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
        $price = [];

        // Set where
        $order = ['time_update DESC', 'id DESC'];
        $where = ['product' => $product];
        if (!empty($type) && in_array($type, ['product', 'property', 'sale'])) {
            $where['type'] = $type;
        }

        // Select
        $select = Pi::model('price', $this->getModule())->select()->where($where)->order($order)->limit(1);
        $row    = Pi::model('price', $this->getModule())->selectWith($select)->current();

        // Get time update
        if (empty($row)) {
            $price = $row->toArray();
        }

        return $price;
    }

    public function makeUpdatePrice($price, $percent)
    {
        // Make new price
        switch (Pi::config('number_currency')) {
            // Set for Iran Rial
            case 'IRR':
                $price = $price + (($price * $percent) / 100);
                $price = ((int)($price / 1000)) * 1000;
                $price = number_format((float)$price, 2, '.', '');
                break;

            default:
                $price = $price + (($price * $percent) / 100);
                $price = number_format((float)$price, 2, '.', '');
                break;
        }

        return $price;
    }
}