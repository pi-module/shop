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
 * Pi::api('property', 'shop')->getPropertyValue($parameter, $type = 'unique_key');
 * Pi::api('property', 'shop')->getList();
 * Pi::api('property', 'shop')->setValue($properties, $product);
 * Pi::api('property', 'shop')->getValue($product);
 */

class Property extends AbstractApi
{
    public function getPropertyValue($parameter, $type = 'unique_key')
    {
        // Get product
        $value               = Pi::model('property_value', $this->getModule())->find($parameter, $type);
        $value               = $value->toArray();
        $value['price_view'] = Pi::api('api', 'shop')->viewPrice($value['price']);
        return $value;
    }

    public function getList()
    {
        // find
        $list   = [];
        $where  = ['status' => 1];
        $order  = ['order ASC', 'id DESC'];
        $select = Pi::model('property', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('property', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $row->toArray();
        }
        return $list;
    }

    public function setValue($properties, $product)
    {
        // Set price array
        $priceList = [];
        //Remove
        Pi::model('property_value', $this->getModule())->delete(['product' => $product]);
        // Add
        foreach ($properties as $propertyId => $propertyValues) {
            foreach ($propertyValues as $propertyValue) {
                if (isset($propertyValue['name']) && !empty($propertyValue['name'])) {
                    // Set array
                    $values               = [];
                    $values['product']    = $product;
                    $values['property']   = $propertyId;
                    $values['name']       = $propertyValue['name'];
                    $values['unique_key'] = isset($propertyValue['unique_key']) ? $propertyValue['unique_key'] : md5(time() + rand(100, 999));
                    $values['stock']      = isset($propertyValue['stock']) ? (int)$propertyValue['stock'] : '';
                    $values['price']      = isset($propertyValue['price']) ? (int)$propertyValue['price'] : '';
                    // Save
                    $row = Pi::model('property_value', $this->getModule())->createRow();
                    $row->assign($values);
                    $row->save();
                    // Add price to array
                    if (isset($propertyValue['price']) && !empty($propertyValue['price']) && $propertyValue['price'] > 0) {
                        $priceList[] = (int)$propertyValue['price'];
                        // Add price log
                        Pi::api('price', 'shop')->addLog((int)$propertyValue['price'], $product, 'property', $row->unique_key);
                    }
                }
            }
        }
        // Check price and update tables
        if (!empty($priceList)) {
            $minPrice = min($priceList);
            Pi::model('product', $this->getModule())->update(
                ['price' => $minPrice],
                ['id' => $product]
            );
            Pi::model('link', $this->getModule())->update(
                ['price' => $minPrice],
                ['product' => $product]
            );
            // Add price log
            Pi::api('price', 'shop')->addLog($minPrice, $product, 'product');
        }
    }

    public function getValue($product)
    {
        $prices = [];
        $values = [];
        $where  = ['product' => $product];
        $order  = ['name ASC'];
        $select = Pi::model('property_value', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('property_value', $this->getModule())->selectWith($select);
        // Make values list
        foreach ($rowSet as $row) {
            if ($row->price > 0) {
                $prices[$row->property][$row->id] = $row->price;
            }
            $values[$row->property][$row->id]               = $row->toArray();
            $values[$row->property][$row->id]['price_view'] = Pi::api('api', 'shop')->viewPrice($row->price);
            $values[$row->property][$row->id]['select']     = 0;
        }
        // Set checked for min price
        foreach ($prices as $property => $priceList) {
            $minPrice = min($priceList);
            foreach ($priceList as $key => $price) {
                if ($price == $minPrice) {
                    $values[$property][$key]['select'] = 1;
                    break;
                }
            }
        }
        return $values;
    }
}