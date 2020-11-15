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

namespace Module\Shop\Controller\Front;

use Module\Shop\Form\PromotionCheckFilter;
use Module\Shop\Form\PromotionCheckForm;
use Pi;
use Pi\Mvc\Controller\ActionController;

class CartController extends ActionController
{
    public function indexAction()
    {
        // Get module
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check order is active or inactive
        if (!$config['order_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('So sorry, At this moment order is inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get basket
        $basket = Pi::api('basket', 'shop')->getBasket();

        // Set promotion form
        $form = new PromotionCheckForm('promotion');
        $form->setAttribute('enctype', 'multipart/form-data');
        $form->setAttribute('action', $this->url('', ['action' => 'promotion']));
        if (isset($basket['data']['promotion']) && !empty($basket['data']['promotion'])) {
            $data = [
                'code' => $basket['data']['promotion'],
            ];
            $form->setData($data);
        }

        // Set title
        $title = __('Basket');

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'cart');
        }

        // Update user processing
        $allowOrder = true;
        if ($config['processing_user']) {

            // Get user
            $userProcessing = Pi::api('user', 'shop')->get(Pi::user()->getId());

            // Check user can make order
            if ($config['processing_disable_order'] && intval($userProcessing['order_active']) == 0) {
                $allowOrder = false;
            }

            // Check user order this product before
            foreach ($basket['products'] as $productSingle) {
                if ($config['processing_order_limit'] && in_array($productSingle['id'], $userProcessing['products'])) {
                    $allowOrder = false;
                }
            }
        }

        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'prepend');
        $this->view()->headKeywords($title, 'prepend');
        $this->view()->setTemplate('checkout-cart');
        $this->view()->assign('config', $config);
        $this->view()->assign('basket', $basket);
        $this->view()->assign('form', $form);
        $this->view()->assign('allowOrder', $allowOrder);
    }

    public function promotionAction()
    {
        // Set template
        $this->view()->setTemplate(false);
        // Check post
        if ($this->request->isPost()) {
            // Get post
            $data = $this->request->getPost();
            // Get basket
            $basket = Pi::api('basket', 'shop')->getBasket();
            if (empty($basket)) {
                $message = __('Your basket is empty');
                $this->jump(['action' => 'index'], $message, 'error');
            } elseif (isset($basket['data']['promotion']) && !empty($basket['data']['promotion'])) {
                $message = sprintf(__('Your already use %s promotion code'), $basket['data']['promotion']);
                $this->jump(['action' => 'index'], $message, 'success');
            }
            // Set promotion form and action
            $form = new PromotionCheckForm('promotion');
            $form->setAttribute('enctype', 'multipart/form-data');
            $form->setInputFilter(new PromotionCheckFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Get promotion
                $where     = [
                    'code'             => _strip($values['code']),
                    'status'           => 1,
                    'time_publish < ?' => time(),
                    'time_expire > ?'  => time(),
                ];
                $select    = $this->getModel('promotion')->select()->where($where)->limit(1);
                $promotion = $this->getModel('promotion')->selectWith($select)->current();
                if (!empty($promotion)) {
                    $promotion = $promotion->toArray();
                    // Update basket
                    Pi::api('basket', 'shop')->updateBasket('promotion', $promotion['code']);
                    // Update used number
                    /* $this->getModel('promotion')->update(
                        array('used' => $promotion['used'] + 1),
                        array('id' => $promotion['id'])
                    ); */
                    // jump
                    $message = __('Promotion code add successfully to your basket');
                    $this->jump(['action' => 'index'], $message, 'success');
                } else {
                    $message = __('Promotion code is not valid');
                    $this->jump(['action' => 'index'], $message, 'error');
                }
            } else {
                $message = __('Promotion code is not valid');
                $this->jump(['action' => 'index'], $message, 'error');
            }
        } else {
            $message = __('Promotion code is not valid');
            $this->jump(['action' => 'index'], $message, 'error');
        }
    }

    public function addAction()
    {
        // Get module
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check order is active or inactive
        if (!$config['order_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('So sorry, At this moment order is inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Check post
        if ($this->request->isPost()) {
            // Get post
            $data = $this->request->getPost()->toArray();
            // Find product
            $product = Pi::api('product', 'shop')->getProductLight(intval($data['id']));
            // Check product
            if (!in_array($product['marketable'], [1, 2])) {
                $message = __('The product was not marketable.');
                $this->jump($product['productUrl'], $message, 'error');
            } else {
                // Check color
                $property = [];
                if (isset($data['property']) && !empty($data['property'])) {
                    $property = $data['property'];
                }
                // Set basket
                Pi::api('basket', 'shop')->setBasket($product['id'], 1, $property, $product['marketable']);
                // Go to cart
                $url = ['', 'module' => $module, 'controller' => 'cart', 'action' => 'index'];
                return $this->redirect()->toRoute('', $url);
            }
        } else {
            // Go to cart
            $url = ['', 'module' => $module, 'controller' => 'cart', 'action' => 'index'];
            return $this->redirect()->toRoute('', $url);
        }
    }

    public function updateAction()
    {
        // Get basket
        $basket = Pi::api('basket', 'shop')->getBasket();
        return [
            'status'   => 1,
            'price'    => $basket['total']['price_view'],
            'discount' => $basket['total']['discount_view'],
            'number'   => $basket['total']['number_view'],
            'total'    => $basket['total']['total_price_view'],
        ];
    }

    public function emptyAction()
    {
        // Set empty
        Pi::api('basket', 'shop')->emptyBasket();
        // Back
        $module = $this->params('module');
        $url    = ['', 'module' => $module, 'controller' => 'index', 'action' => 'index'];
        $this->jump($url, __('Your cart are empty'), 'success');
    }

    public function basketAction()
    {
        // Get basket
        $basket = Pi::api('basket', 'shop')->getBasket();

        // Get info from url
        $process = $this->params('process', 'number');
        $product = $this->params('product', 1);
        $module  = $this->params('module');

        // Set return
        $return                 = [];
        $return['message']      = __('Please select product');
        $return['id']           = $product;
        $return['ajaxStatus']   = 0;
        $return['actionStatus'] = 0;
        $return['actionName']   = $process;

        // process
        switch ($process) {
            case 'remove':
                Pi::api('basket', 'shop')->removeProduct($product);
                $return['message']      = __('Selected product removed from your cart');
                $return['ajaxStatus']   = 1;
                $return['actionStatus'] = 1;
                break;

            case 'number':
                $number = $basket['products'][$product]['number'];
                if ($number > 0) {
                    $getNumber = $this->params('number');
                    $newNumber = $number + $getNumber;
                    if ($newNumber > 0) {

                        // Set price
                        $price = $basket['products'][$product]['price'];

                        // Get property info
                        if (isset($basket['products'][$product]['property']) && !empty($basket['products'][$product]['property'])) {
                            $propertyList = Pi::api('property', 'shop')->getList();
                            foreach ($basket['products'][$product]['property'] as $property) {
                                $propertyInfoSingle = Pi::api('property', 'shop')->getPropertyValue($property['unique_key']);
                                if ($propertyList[$propertyInfoSingle['property']]['influence_price']) {
                                    $price = $propertyInfoSingle['price'];
                                }
                            }
                        }

                        // Update number
                        $newTotal = $newNumber * $price;
                        Pi::api('basket', 'shop')->updateBasket('number', $product, $newNumber);

                        // Set return
                        $return['message']      = __('Update number');
                        $return['actionNumber'] = $newNumber;
                        $return['ajaxStatus']   = 1;
                        $return['actionStatus'] = 1;
                        $return['actionTotal']  = Pi::api('api', 'shop')->viewPrice($newTotal);
                    } else {
                        $return['message']      = __('You can not set product number to 0');
                        $return['ajaxStatus']   = 1;
                        $return['actionStatus'] = 0;
                    }
                }
                break;
        }

        // return
        return $return;
    }

    public function completeAction()
    {
        // Set template
        $this->view()->setTemplate(false);

        // Get basket
        $basket = Pi::api('basket', 'shop')->getBasket();

        // Set total product price
        $total = 0.00;

        // Set can pay array
        $canPay = [];

        // Get module
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check order is active or inactive
        if (!$config['order_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('So sorry, At this moment order is inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Set order array
        $order                   = [];
        $order['module_name']    = $module;
        $order['type_payment']   = 'onetime';
        $order['type_commodity'] = 'product';
        $order['total_discount'] = $basket['total']['discount'];
        $order['total_shipping'] = $basket['total']['shipping'];
        $order['total_packing']  = 0;
        $order['total_setup']    = 0;
        $order['total_vat']      = 0;

        // Set products to order
        foreach ($basket['products'] as $product) {

            // Set price
            $price = $product['price'];

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

            // Set single product
            $singelProduct = [
                'product'        => $product['id'],
                'product_price'  => $price,
                'discount_price' => 0,
                'shipping_price' => $product['price_shipping'],
                'packing_price'  => 0,
                'vat_price'      => 0,
                'number'         => $product['number'],
                'title'          => $product['title'],
                'extra'          => json_encode($product['property']),
            ];

            // Set order product
            $order['product'][$product['id']] = $singelProduct;

            // Set can check array
            $canPay[$product['id']] = $product['can_pay'];

            // Update total price
            $total = $price + $total;
        }

        // Check can pay or not
        $order['can_pay'] = (in_array(2, $canPay)) ? 2 : 1;

        // Check promotion
        if (isset($basket['data']['promotion']) && !empty($basket['data']['promotion'])) {

            // Get promotion
            $where     = [
                'code'             => _strip($basket['data']['promotion']),
                'status'           => 1,
                'time_publish < ?' => time(),
                'time_expire > ?'  => time(),
            ];
            $select    = $this->getModel('promotion')->select()->where($where)->limit(1);
            $promotion = $this->getModel('promotion')->selectWith($select)->current();

            // Check promotion
            if (!empty($promotion)) {
                $promotion = $promotion->toArray();

                // Update used number
                $this->getModel('promotion')->update(
                    ['used' => $promotion['used'] + 1],
                    ['id' => $promotion['id']]
                );

                // Use
                $order['promotion_type']  = 'shop-promotion';
                $order['promotion_value'] = _strip($promotion['code']);

                // Set credit
                if ($promotion['partner'] > 0) {
                    $credit = 0;
                    switch ($promotion['type']) {
                        case 'percent':
                            if ($promotion['percent_partner'] > 0) {
                                $credit = ($total * $promotion['percent_partner']) / 100;
                            }
                            break;

                        case 'price':
                            if ($promotion['price_partner'] > 0) {
                                if ($promotion['price_partner'] > $total) {
                                    $credit = $promotion['price_partner'] - $total;
                                } else {
                                    $credit = $total;
                                }
                            }
                            break;
                    }

                    if ($credit > 0) {
                        $order['credit'] = [
                            'uid'                => $promotion['partner'],
                            'amount'             => $credit,
                            'status_fluctuation' => 'increase',
                            'status_action'      => 'automatic',
                            'message_user'       => sprintf(__('Increase credit from shop module by use <strong>%s</strong> code'), _strip($promotion['code'])),
                            'message_admin'      => sprintf(__('Increase credit from shop module by use <strong>%s</strong> code'), _strip($promotion['code'])),
                        ];
                    }
                }
            }
        }

        // Unset shop session
        Pi::api('basket', 'shop')->emptyBasket();

        // Update user processing
        $allowOrder = true;
        if ($config['processing_user']) {

            // Set products
            $productList = [];

            // Get user
            $userProcessing = Pi::api('user', 'shop')->get(Pi::user()->getId());

            // Check user can make order
            if ($config['processing_disable_order'] && intval($userProcessing['order_active']) == 0) {
                $allowOrder = false;
            }

            // Check user order this product before
            foreach ($basket['products'] as $productSingle) {
                if ($config['processing_order_limit'] && in_array($productSingle['id'], $userProcessing['products'])) {
                    $allowOrder = false;
                } else {
                    $productList[$productSingle['id']] = $productSingle['id'];
                }
            }

            // Update user
            if ($allowOrder) {
                $userProductList = [];
                $productList = array_unique(array_merge($userProcessing['products'], $productList));
                foreach ($productList as $productSingle) {
                    $userProductList[$productSingle] = $productSingle;
                }

                $orderActive = 1;
                if (intval($config['processing_disable_order']) == 1) {
                    $orderActive = 0;
                }

                $params      = [
                    'uid'             => $userProcessing['uid'],
                    'products'        => $userProductList,
                    'order_active'    => $orderActive,
                    'time_last_order' => time(),
                    'product_count'   => count($productList),
                    'product_fee'     => $userProcessing['product_fee'] + $total,
                ];

                // Update
                Pi::api('user', 'shop')->update($params);
            }
        }

        // Check order is active or inactive
        if ($allowOrder) {
            // Set and go to order
            $url = Pi::api('order', 'order')->setOrderInfo($order);
            Pi::service('url')->redirect($url);
        } else {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('You ar not allowed to make new order'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
    }

    /* public function finishAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Check order is active or inactive
        if (!$this->config('order_active')) {
            $url = array('', 'module' => $this->params('module'), 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('So sorry, At this moment order is inactive'));
        }
        // Get info from url
        $id = $this->params('id');
        $module = $this->params('module');
        // Find order
        $order = Pi::api('order', 'order')->getOrder($id);
        // Check order
        if (!$order['id']) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('Order not set.'));
        }
        // Check user
        if ($order['uid'] != Pi::user()->getId()) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('It not your order.'));
        }
        // Check status payment
        if ($order['status_payment'] != 2) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('This order not pay'));
        }
        // Check time payment
        $time = time() - 3600;
        if ($time > $order['time_payment']) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('This is old order and you pay it before'));
        }
        // Set links
        $order['order_link'] = Pi::url($this->url('order', array(
            'module' => 'order',
            'controller' => 'detail',
            'action' => 'index',
            'id' => $order['id']
        )));
        $order['user_link'] = Pi::url($this->url('order', array(
            'module' => 'order',
            'controller' => 'index',
            'action' => 'index',
        )));
        $order['index_link'] = Pi::url($this->url('', array(
            'module' => $module,
            'controller' => 'index',
            'action' => 'index',
        )));
        // Get invoice information
        Pi::service('i18n')->load(array('module/order', 'default'));
        $invoices = Pi::api('invoice', 'order')->getInvoiceFromOrder($order['id']);
        // Set view
        $this->view()->setTemplate('checkout-finish');
        $this->view()->assign('order', $order);
        $this->view()->assign('invoices', $invoices);
    } */

    /* protected function setEmpty()
    {
        if (isset($_SESSION['shop']['cart'])) {
            unset($_SESSION['shop']['cart']);
        }
    } */

    /* protected function setInvoice()
    {
    	// Get cart
        $cart = $_SESSION['shop']['cart'];
        // Set invoice
        $invoice = array();
        $invoice['total']['price'] = 0;
        $invoice['total']['number'] = 0;
        $invoice['total']['discount'] = 0;
        $invoice['total']['shipping'] = 0;
        //$invoice['total']['plan'] = 0;
        // Set invoice product
    	foreach ($cart['product'] as $product) {
    		// Set product item
            $item = array();
            $item['id'] = $product['id'];
            $item['number'] = $product['number'];
            $item['price'] = $product['price'];
            $item['price_view'] = Pi::api('api', 'shop')->viewPrice($item['price']);
            $item['discount'] = 0;
            $item['total'] = ($product['number'] * $product['price']);
            $item['total_view'] = Pi::api('api', 'shop')->viewPrice($item['total']);
            $invoice['product'][$product['id']] = $item;
            // Set total
    		$invoice['total']['price'] = $invoice['total']['price'] + ($product['price'] * $product['number']);
    		$invoice['total']['number'] = $invoice['total']['number'] + $product['number'];
    		$invoice['total']['discount'] = $invoice['total']['discount'] + 0;
            // Update cart product
            $cart['product'][$item['id']]['total'] = $item['total'];
            $cart['product'][$item['id']]['total_view'] = $item['total_view'];
            // Set plan
            //$invoice['total']['plan'] = $product['plan'];
    	}
        // Set price
        //$invoice['total']['location'] = (isset($cart['invoice']['total']['location'])) ? $cart['invoice']['total']['location'] : 0;
        //$invoice['total']['delivery'] = (isset($cart['invoice']['total']['delivery'])) ? $cart['invoice']['total']['delivery'] : 0;
        //$invoice['total']['payment'] = (isset($cart['invoice']['total']['payment'])) ? $cart['invoice']['total']['payment'] : 'offline';
        //$invoice['total']['location_title'] = (isset($cart['invoice']['total']['location_title'])) ? $cart['invoice']['total']['location_title'] : '';
        //$invoice['total']['delivery_title'] = (isset($cart['invoice']['total']['delivery_title'])) ? $cart['invoice']['total']['delivery_title'] : '';
        //$invoice['total']['payment_title'] = (isset($cart['invoice']['total']['payment_title'])) ? $cart['invoice']['total']['payment_title'] : __('Offline');
        $invoice['total']['shipping'] = (isset($cart['invoice']['total']['shipping'])) ? intval($cart['invoice']['total']['shipping']) : 0;
        $invoice['total']['total_price'] = intval($invoice['total']['price'] - $invoice['total']['discount']) + $invoice['total']['shipping'];
        $invoice['total']['price_view'] = Pi::api('api', 'shop')->viewPrice($invoice['total']['price']);
    	$invoice['total']['discount_view'] = Pi::api('api', 'shop')->viewPrice($invoice['total']['discount']);
        $invoice['total']['shipping_view'] = Pi::api('api', 'shop')->viewPrice($invoice['total']['shipping']);
    	$invoice['total']['total_price_view'] = Pi::api('api', 'shop')->viewPrice($invoice['total']['total_price']);
    	// Set Seaaion
    	$cart['invoice'] = $invoice;
        $_SESSION['shop']['cart'] = $cart;
        // return invoice
        return $invoice;
    } */

    /* protected function setProduct($product)
    {
        $cart = $_SESSION['shop']['cart'];
        // Set number
        if (isset($cart['product'][$product['id']])
            && !empty($cart['product'][$product['id']]))
        {
            if (isset($cart['product'][$product['id']]['number'])
                && !empty($cart['product'][$product['id']]['number']))
            {
                $product['number'] = $cart['product'][$product['id']]['number'] + 1;
            } else {
                $product['number'] = 1;
            }
        } else {
            $product['number'] = 1;
        }
        // Set total price
        $product['total'] = intval(($product['price'] * $product['number']));
        $product['total_view'] = Pi::api('api', 'shop')->viewPrice($product['total']);
        // Set session
        $cart['product'][$product['id']] = $product;
        $_SESSION['shop']['cart'] = $cart;
    } */
}
