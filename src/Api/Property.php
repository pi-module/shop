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
 * Pi::api('property', 'shop')->Get();
 * Pi::api('property', 'shop')->Set($properties, $product);
 * Pi::api('property', 'shop')->Form($values);
 */

class Property extends AbstractApi
{
    public function Get()
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

    public function Set($properties, $product)
    {
        //Remove
        Pi::model('property_value', $this->getModule())->delete(array('product' => $product));

        echo '<pre>';
        print_r($properties);
        echo '</pre>';
        // Add
        foreach ($properties as $propertyId => $propertyValues) {
            foreach ($propertyValues as $propertyValue) {
                if (isset($propertyValue['name']) && !empty($propertyValue['name'])) {
                    // Set array
                    $values = array();
                    $values['product'] = $product;
                    $values['property'] = $propertyId;
                    $values['name'] = $propertyValue['name'];
                    $values['stock'] = isset($propertyValue['stock']) ? $propertyValue['stock'] : '';
                    $values['price'] = isset($propertyValue['price']) ? $propertyValue['price'] : '';
                    // Save
                    $row = Pi::model('property_value', $this->getModule())->createRow();
                    $row->assign($values);
                    $row->save();
                }
            }
        }
    }

    public function Form($product)
    {
        $values = array();
        $where = array('product' => $product);
        $select = Pi::model('property_value', $this->getModule())->select()->where($where);
        $rowset = Pi::model('property_value', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $values[$row->property][$row->id] = $row->toArray();
        }
        return $values;
    }
}