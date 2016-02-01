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
 * Pi::api('basket', 'shop')->setBasketSession($id, $number, $properties);
 * Pi::api('basket', 'shop')->getBasketSession();
 * Pi::api('basket', 'shop')->updateBasket($id, $number);
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

        if ($uid == 0) {
            $_SESSION['session_order'] = $basket->value;
        }
    }

    public function getBasket()
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        if ($uid > 0) {
            $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order'])) {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order'], 'value');
        }
        // Check basket
        if (!isset($basket) || empty($basket)) {
            return '';
        }
        return $this->canonizeBasket($basket);
    }

    /* public function setBasketSession($id, $number = 1, $properties = array())
    {
        $_SESSION['session_order'] = array(
            'id' => $id,
            'number' => $number,
            'properties' => $properties
        );
    }

    public function getBasketSession()
    {
        if (isset($_SESSION['session_order']) && !empty($_SESSION['session_order'])) {
            $this->setBasket(
                $_SESSION['session_order']['id'],
                $_SESSION['session_order']['number'],
                $_SESSION['session_order']['properties']
            );
            unset($_SESSION['session_order']);
            return $this->getBasket();
        } else {
            return '';
        }
    } */

    public function updateBasket($id, $number)
    {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        if ($uid > 0) {
            $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order'])) {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order'], 'value');
        }
        // Check basket
        if (!isset($basket) || empty($basket)) {
            return '';
        }
        // Update data
        $data = json::decode($basket->data, true);
        $data['product'][$id]['number'] = $number;
        $basket->data = json::encode($data);
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
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order'])) {
            Pi::model('basket', $this->getModule())->delete(
                array('value' => $_SESSION['session_order'])
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
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order'])) {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order'], 'value');
        }
        // Check basket
        if (!isset($basket) || empty($basket)) {
            return '';
        }
        // Set data
        $data = json::decode($basket->data, true);
        $data['time'] = time();
        unset($data['product'][$id]);
        // Set basket
        $basket->data = json::encode($data);
        $basket->save();
    }

    public function basketBlockNumber() {
        // Get uid
        $uid = Pi::user()->getId();
        // find backet
        if ($uid > 0) {
            $basket = Pi::model('basket', $this->getModule())->find($uid, 'uid');
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order'])) {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order'], 'value');
        }
        // Check basket
        if (!isset($basket) || empty($basket)) {
            return 0;
        } else {
            $number = 0;
            $data = json::decode($basket['data'], true);
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
        } elseif (isset($_SESSION['session_order']) && !empty($_SESSION['session_order'])) {
            $basket = Pi::model('basket', $this->getModule())->find($_SESSION['session_order'], 'value');
        }
        // Check basket
        if (isset($basket) && !empty($basket)) {
            $data = json::decode($basket['data'], true);
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
        $basket['data'] = json::decode($basket['data'], true);
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
            $productInfo = Pi::api('product', 'shop')->getProductLight($product['id']);
            $productInfo['can_pay'] = $product['can_pay'];
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