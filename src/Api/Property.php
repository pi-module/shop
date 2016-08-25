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
        $value = Pi::model('property_value', $this->getModule())->find($parameter, $type);
        $value = $value->toArray();
        $value['price_view'] = Pi::api('api', 'shop')->viewPrice($value['price']);
        return $value;
    }

    public function getList()
    {
        // find
        $list = array();
        $where = array('status' => 1);
        $order = array('order ASC', 'id DESC');
        $select = Pi::model('property', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('property', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        return $list;
    }

    public function setValue($properties, $product)
    {
        // Set price array
        $priceList = array();
        //Remove
        Pi::model('property_value', $this->getModule())->delete(array('product' => $product));
        // Add
        foreach ($properties as $propertyId => $propertyValues) {
            foreach ($propertyValues as $propertyValue) {
                if (isset($propertyValue['name']) && !empty($propertyValue['name'])) {
                    // Set array
                    $values = array();
                    $values['product'] = $product;
                    $values['property'] = $propertyId;
                    $values['name'] = $propertyValue['name'];
                    $values['unique_key'] = isset($propertyValue['unique_key']) ? $propertyValue['unique_key'] : md5(time() + rand(100,999));
                    $values['stock'] = isset($propertyValue['stock']) ? $propertyValue['stock'] : '';
                    $values['price'] = isset($propertyValue['price']) ? $propertyValue['price'] : '';
                    // Save
                    $row = Pi::model('property_value', $this->getModule())->createRow();
                    $row->assign($values);
                    $row->save();
                    // Add price to array
                    if (isset($propertyValue['price']) && !empty($propertyValue['price'])) {
                        $priceList[] = $propertyValue['price'];
                    }
                }
            }
        }
        // Check price and update tables
        if (!empty($priceList)) {
            $minPrice = min($priceList);
            Pi::model('product', $this->getModule())->update(
                array('price' => $minPrice),
                array('id' => $product)
            );
            Pi::model('link', $this->getModule())->update(
                array('price' => $minPrice),
                array('product' => $product)
            );
        }
    }

    public function getValue($product)
    {
        $values = array();
        $where = array('product' => $product);
        $select = Pi::model('property_value', $this->getModule())->select()->where($where);
        $rowset = Pi::model('property_value', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $values[$row->property][$row->id] = $row->toArray();
            $values[$row->property][$row->id]['price_view'] = Pi::api('api', 'shop')->viewPrice($row->price);
        }
        return $values;
    }
}