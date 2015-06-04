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
use Zend\Json\Json;

/*
 * Pi::api('basket', 'shop')->setBasket($id, $number, $properties);
 * Pi::api('basket', 'shop')->getBasket();
 * Pi::api('basket', 'shop')->emptyBasket();
 * Pi::api('basket', 'shop')->removeProduct($id);
 */

class Basket extends AbstractApi
{
    public function setBasket($id, $number = 1, $properties = array())
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        // Get property list
        $propertyList = Pi::api('property', 'shop')->getList();
        // Set product property
        $productProperty = array();
        foreach ($properties as $key => $property) {
            $productProperty[$key] = array(
                'id' => $propertyList[$key]['id'],
                'title' => $propertyList[$key]['title'],
                'value' => $property,
            );
        }
        // Set product
        $product = array(
            'id'        => $id,
            'number'    => $number,
            'property'  => $productProperty,
        );
        // Check basket
        if (empty($basket)) {
        	// Set data
        	$data = array();
        	$data['time'] = time();
        	$data['product'][$id] = $product;
        	// Set basket
        	$basket = Pi::model('basket', $this->getModule())->createRow();
        	$basket->uid = $uid;
        	$basket->value = uniqid('basket-');
        	$basket->data = json::encode($data);
        	$basket->save();
        } else {
        	// Set data
        	$data = json::decode($basket->data, true);
        	$data['time'] = time();
            $data['product'][$id] = $product;
        	// Set basket
        	$basket->data = json::encode($data);
        	$basket->save();
        }
    }

    public function getBasket()
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        // Check basket
        if (empty($basket)) {
        	return '';
        }
        // to array
        $basket = $basket->toArray();
        // Set data
        $basket['data'] = json::decode($basket['data'], true);
        // Set total empty
        $total = array(
            'price'       => 0,
            'discount'    => 0,
            'number'      => 0,
            'shipping'    => 0,
            'total_price' => 0,
        );
        // Set products
        $basket['products'] = array();
        foreach ($basket['data']['product'] as $product) {
        	$productInfo = Pi::api('product', 'shop')->getProductLight($product['id']);
        	$productInfo['number'] = $product['number'];
        	$productInfo['property'] = $product['property'];
        	$productInfo['total'] = $productInfo['price'] * $product['number'];
        	$productInfo['total_view'] = Pi::api('api', 'shop')->viewPrice($productInfo['total']);
        	$basket['products'][$product['id']] = $productInfo;
            // Set total
        	$total['price'] = $total['price'] + $productInfo['price'];
        	$total['number'] = $total['number'] + $product['number'];
        	$total['total_price'] = $total['total_price'] + $productInfo['total'];
        }
        // Set total
        $basket['total'] = array(
            'price'             => $total['price'],
            'price_view'        => Pi::api('api', 'shop')->viewPrice($total['price']),
            'discount'          => $total['discount'],
            'discount_view'     => Pi::api('api', 'shop')->viewPrice($total['discount']),
            'number'            => $total['number'],
            'number_view'       => _number($total['number']),
            'shipping'          => $total['shipping'],
            'shipping_view'     => Pi::api('api', 'shop')->viewPrice($total['shipping']),
            'total_price'       => $total['total_price'],
            'total_price_view'  => Pi::api('api', 'shop')->viewPrice($total['total_price']),
        );
        // return
        return $basket;
    }

    public function emptyBasket()
    {
        // Get uid
        $uid = Pi::user()->getId();
        // delete
        Pi::model('basket', $this->getModule())->delete(
        	array('uid' => $uid)
        );
    }

    public function removeProduct($id)
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        // Set data
        $data = json::decode($basket->data, true);
        $data['time'] = time();
        unset($data['product'][$id]) ;
        // Set basket
        $basket->data = json::encode($data);
        $basket->save();
    }
}