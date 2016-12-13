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
 * Pi::api('basket', 'shop')->setBasket($id, $number, $properties);
 * Pi::api('basket', 'shop')->getBasket();
 * Pi::api('basket', 'shop')->updateBasket($type, $value, $number);
 * Pi::api('basket', 'shop')->emptyBasket();
 * Pi::api('basket', 'shop')->removeProduct($id);
 * Pi::api('basket', 'shop')->basketBlockNumber();
 * Pi::api('basket', 'shop')->basketBlockInfo();
 */

class Basket extends AbstractApi
{
    public function setBasket($id, $number = 1, $properties = array(), $canPay = 1)
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        // Get property list
        $propertyList = Pi::api('property', 'shop')->getList();
        // Set product property
        $productProperty = array();
        foreach ($properties as $key => $propertyKey) {
            $property = Pi::api('property', 'shop')->getPropertyValue($propertyKey);
            $productProperty[$key] = array(
                'id' => $propertyList[$key]['id'],
                'title' => $propertyList[$key]['title'],
                'value' => $property['name'],
                'unique_key' => $property['unique_key'],
            );
        }
        // Set product
        $product = array(
            'id' => $id,
            'number' => $number,
            'property' => $productProperty,
            'can_pay' => $canPay,
        );
        // Check basket
        if (empty($basket)) {
            // Set data
            $data = array();
            $data['time'] = time();
            $data['promotion'] = '';
            $data['product'][$id] = $product;
            // Set basket
            $basket = Pi::model('basket', $this->getModule())->createRow();
            $basket->uid = $uid;
            $basket->value = uniqid('basket-');
            $basket->data = json_encode($data);
            $basket->save();
        } else {
            // Set data
            $data = json_decode($basket->data, true);
            $data['time'] = time();
            $data['product'][$id] = $product;
            if (!isset($data['promotion']) || empty($data['promotion'])) {
                $data['promotion'] = '';
            }
            // Set basket
            $basket->data = json_encode($data);
            $basket->save();
        }

        if ($uid == 0) {
            $_SESSION['session_order'] = array(
                'module' => 'shop',
                'value' => $basket->value,
            );
        }
    }

    public function getBasket()
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        if ($uid > 0) {
            $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order']) && $_SESSION['session_order']['module'] == 'shop') {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order']['value'], 'value');
        }
        // Check basket
        if (!isset($basket) || empty($basket)) {
            return '';
        }
        return $this->canonizeBasket($basket);
    }

    public function updateBasket($type, $value, $number = '')
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        if ($uid > 0) {
            $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order']) && $_SESSION['session_order']['module'] == 'shop') {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order']['value'], 'value');
        }
        // Check basket
        if (!isset($basket) || empty($basket)) {
            return '';
        }
        // Update data
        $data = json_decode($basket->data, true);
        switch ($type) {
            case 'number':
                $data['product'][$value]['number'] = $number;
                break;
            
            case 'promotion':
                $data['promotion'] = $value;
                break;
        }
        $basket->data = json_encode($data);
        // Save
        $basket->save();
    }

    public function emptyBasket()
    {
        // Get uid
        $uid = Pi::user()->getId();
        // delete
        if ($uid > 0) {
            Pi::model('basket', $this->getModule())->delete(
                array('uid' => $uid)
            );
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order']) && $_SESSION['session_order']['module'] == 'shop') {
            Pi::model('basket', $this->getModule())->delete(
                array('value' => $_SESSION['session_order']['value'])
            );
        }
    }

    public function removeProduct($id)
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        if ($uid > 0) {
            $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order']) && $_SESSION['session_order']['module'] == 'shop') {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order']['value'], 'value');
        }
        // Check basket
        if (!isset($basket) || empty($basket)) {
            return '';
        }
        // Set data
        $data = json_decode($basket->data, true);
        $data['time'] = time();
        unset($data['product'][$id]);
        // Set basket
        $basket->data = json_encode($data);
        $basket->save();
    }

    public function basketBlockNumber() {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        if ($uid > 0) {
            $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order']) && $_SESSION['session_order']['module'] == 'shop') {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order']['value'], 'value');
        }
        // Check basket
        if (!isset($basket) || empty($basket)) {
            return 0;
        } else {
            $number = 0;
            $data = json_decode($basket['data'], true);
            foreach ($data['product'] as $product) {
                $number = $number + $product['number'];
            }
            return $number;
        }
    }

    public function basketBlockInfo() {
        $list = array();
        $number = 0;
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        if ($uid > 0) {
            $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order']) && $_SESSION['session_order']['module'] == 'shop') {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order']['value'], 'value');
        }
        // Check basket
        if (isset($basket) && !empty($basket)) {
            $data = json_decode($basket['data'], true);
            foreach ($data['product'] as $product) {
                // Set list
                $productInfo = Pi::api('product', 'shop')->getProductLight($product['id']);
                $productInfo['number'] = $product['number'];
                $productInfo['number_view'] = _number($product['number']);
                $productInfo['property'] = $product['property'];
                $productInfo['total'] = $productInfo['price'] * $product['number'];
                $productInfo['total_view'] = Pi::api('api', 'shop')->viewPrice($productInfo['total']);
                $list[$product['id']] = $productInfo;
                // Set number
                $number = $number + $product['number'];
            }
        }
        return array(
            'list' => $list,
            'number' => $number
        );
    }

    public function canonizeBasket($basket)
    {
        // to array
        $basket = $basket->toArray();
        // Set data
        $basket['data'] = json_decode($basket['data'], true);
        // Set total empty
        $total = array(
            'price' => 0,
            'discount' => 0,
            'number' => 0,
            'shipping' => 0,
            'total_price' => 0,
            'can_pay' => 1,
        );
        // Set products
        $basket['products'] = array();
        foreach ($basket['data']['product'] as $product) {
            // Get product info
            $productInfo = Pi::api('product', 'shop')->getProductLight($product['id']);
            // Set price
            $price = $productInfo['price'];
            // Get property info
            if (isset($product['property']) && !empty($product['property'])) {
                $propertyList = Pi::api('property', 'shop')->getList();
                foreach ($product['property'] as $property) {
                    $propertyInfoSingle = Pi::api('property', 'shop')->getPropertyValue($property['unique_key']);
                    if ($propertyList[$propertyInfoSingle['property']]['influence_price']) {
                        $price = $propertyInfoSingle['price'];
                    }
                }
            }
            // Set product info
            $productInfo['can_pay'] = $product['can_pay'];
            $productInfo['number'] = $product['number'];
            $productInfo['property'] = $product['property'];
            $productInfo['price_single'] = $price;
            $productInfo['price_single_view'] = Pi::api('api', 'shop')->viewPrice($price);
            $productInfo['total'] = $price * $product['number'];
            $productInfo['total_view'] = Pi::api('api', 'shop')->viewPrice($productInfo['total']);
            $basket['products'][$product['id']] = $productInfo;
            // Set total
            $total['price'] = $total['price'] + $price;
            $total['number'] = $total['number'] + $product['number'];
            $total['total_price'] = $total['total_price'] + $productInfo['total'];
        }
        // Set promotion
        if (isset($basket['data']['promotion']) && !empty($basket['data']['promotion'])) {
            $promotion = Pi::model('promotion', $this->getModule())->find($basket['data']['promotion'], 'code');
            if (!empty($promotion)) {
                $promotion = $promotion->toArray();
                switch ($promotion['type']) {
                    case 'percent':
                        $total['discount'] = ($total['total_price'] * $promotion['percent']) / 100;
                        break;

                    case 'price':
                        $total['discount'] = $promotion['price'];
                        break;
                }
            }
        }
        // Set total price
        $total['total_price'] = $total['total_price'] - $total['discount'];
        // Set total
        $basket['total'] = array(
            'price' => $total['price'],
            'price_view' => Pi::api('api', 'shop')->viewPrice($total['price']),
            'discount' => $total['discount'],
            'discount_view' => Pi::api('api', 'shop')->viewPrice($total['discount']),
            'number' => $total['number'],
            'number_view' => _number($total['number']),
            'shipping' => $total['shipping'],
            'shipping_view' => Pi::api('api', 'shop')->viewPrice($total['shipping']),
            'total_price' => $total['total_price'],
            'total_price_view' => Pi::api('api', 'shop')->viewPrice($total['total_price']),
        );
        // return
        return $basket;
    }
}