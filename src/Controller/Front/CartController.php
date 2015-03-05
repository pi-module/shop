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

namespace Module\Shop\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Zend\Json\Json;

class CartController extends ActionController
{
    public function indexAction()
    {
    	// Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Check order is active or inactive
        if (!$this->config('order_active')) {
            $url = array('', 'module' => $this->params('module'), 'controller' => 'index');
            $this->jump($url, __('So sorry, At this moment order is inactive'), 'error');
        }
        // Set Invoice
        $this->setInvoice();
    	$cart = $_SESSION['shop']['cart'];
    	if (empty($cart['product'])) {
            $module = $this->params('module');
        	$url = array('', 'module' => $module, 'controller' => 'index');
            $this->jump($url, __('Your cart are empty.'), 'error');
    	}
    	$this->view()->setTemplate('checkout_cart');
    	$this->view()->assign('cart', $cart);
    }

    public function addAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Check order is active or inactive
        if (!$this->config('order_active')) {
            $url = array('', 'module' => $this->params('module'), 'controller' => 'index');
            $this->jump($url, __('So sorry, At this moment order is inactive'), 'error');
        }
        // Get info from url
        $slug = $this->params('slug');
        $plan = $this->params('plan');
        $module = $this->params('module');
        // Set view
        $this->view()->setTemplate(false);
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Find product
        $product = $this->getModel('product')->find($slug, 'slug');
        $product = Pi::api('product', 'shop')->canonizeProductLight($product);
        $product['plan'] = $plan;
        // Check product
        if (!$product['marketable']) {
        	$url = array('', 'module' => $module, 'controller' => 'index');
            $this->jump($url, __('The product was not marketable.'), 'error');
        } else {
            // Set total price
            $this->setProduct($product);
            // Set Invoice
            $this->setInvoice();
            // Go to cart
            $url = array('', 'module' => $module, 'controller' => 'cart', 'action' => 'index');
            return $this->redirect()->toRoute('', $url);
        }
    }

    public function emptyAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Set empty
        $this->setEmpty();
        // Back
        $module = $this->params('module');
        $url = array('', 'module' => $module, 'controller' => 'index');
        $this->jump($url, __('Your cart are empty'), 'success');
    }

    public function basketAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Set cart
        $cart = $_SESSION['shop']['cart'];
        // Get info from url
        $process = $this->params('process');
        $product = $this->params('product');
        $module = $this->params('module');
        // Set return
        $return = array();
        $return['message'] = __('Please select product');
        $return['id'] = $product;
        $return['ajaxStatus'] = 0;
        $return['actionStatus'] = 0;
        $return['actionName'] = $process;
        // process
        switch ($process) {
        	case 'remove':
                unset($cart['product'][$product]);
        	    $return['message'] = __('Selected product removed from your cart');
                $return['ajaxStatus'] = 1;
                $return['actionStatus'] = 1;
        		break;

        	case 'number':
        	    $number = $cart['product'][$product]['number'];
        	    if ($number > 0) {
        	    	$getNumber = $this->params('number');
        	    	$newNumber = $number + $getNumber;
        	    	if ($newNumber > 0) {
                        $newTotal = $newNumber * $cart['product'][$product]['price'];
        	    		$cart['product'][$product]['number'] = $newNumber;
                        $cart['product'][$product]['total'] = $newTotal;
        	    		$return['message'] = __('Update number');
                        $return['actionNumber'] = $newNumber;
                        $return['ajaxStatus'] = 1;
                        $return['actionStatus'] = 1;
                        $return['actionTotal'] = $newTotal;
        	    	} else {
        	    		$return['message'] = __('You can not set product number to 0');
                        $return['ajaxStatus'] = 1;
                        $return['actionStatus'] = 0;
        	    	}
        	    }
        		break;
        }
        // Set session
        $_SESSION['shop']['cart'] = $cart;
        // Set Invoice
        $this->setInvoice();
        // return
        return $return;
    }

    public function updateAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Set Invoice
        $this->setInvoice();
        // Set cart
        $invoice = $_SESSION['shop']['cart']['invoice']['total'];
        // Set data
        $data = array();
        $data['price'] = $invoice['price'];
        $data['discount'] = $invoice['discount'];
        $data['number'] = $invoice['number'];
        $data['total'] = $invoice['total_price'];
        // Set return
        $return = array();
        $return['status'] = 1;
        $return['data'] = $data;
        return $return;
    }

    public function completeAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Set template
        $this->view()->setTemplate(false);
        // Set cart
        $cart = $_SESSION['shop']['cart'];
        // Set order array
    	$order = array();
    	$order['module_name'] = $this->params('module');
        $order['type'] = $this->config('order_type');
        if ($this->config('order_type') == 'installment') {
            $order['plan'] = $cart['invoice']['total']['plan'];
        }
    	// Get product list
    	$products = $cart['invoice']['product'];
        // Set products to order
    	foreach ($products as $product) {
    		$singelProduct =  array(
    			'product'         => $product['id'],
    			'product_price'   => $product['price'],
    			'discount_price'  => 0,
    			'shipping_price'  => 0,
    			'packing_price'   => 0,
    			'vat_price'       => 0,
    			'number'          => $product['number'],
                'title'           => '',
    		);
    		$order['product'][$product['id']] = $singelProduct;
    	}
        // Unset shop session
        $this->setEmpty();
        // Set and go to order
        $url = Pi::api('order', 'order')->setOrderInfo($order);
        Pi::service('url')->redirect($url);
    }

    /* public function finishAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Check order is active or inactive
        if ($this->config('order_method') == 'inactive') {
            $url = array('', 'module' => $this->params('module'), 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('So sorry, At this moment order is inactive'));
        }
        // Get info from url
        $id = $this->params('id');
        $module = $this->params('module');
        // Find order
        $order = $this->getModel('order')->find($id);
        // Check order
        if (!$order->id) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('Order not set.'));
        }
        // Check user
        if ($order->uid != Pi::user()->getId()) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('It not your order.'));
        }
        // Check status payment
        if ($order->payment_method == 'online' && $order->status_payment != 2) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('This order not pay'));
        }
        // Check time payment
        $time = time() - 3600;
        if ($order->payment_method == 'online') {
            if ($time > $order->time_payment) {
                $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
                $this->jump($url, __('This is old order and you pay it before'));
            }
        } else {
            if ($time > $order->time_create) {
                $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
                $this->jump($url, __('This is old order and you pay it before'));
            }
        }
        // canonize Order
        $order = Pi::api('order', 'shop')->canonizeOrder($order);
        // Set links
        $order['order_link'] = Pi::url($this->url('', array(
            'module'      => $module, 
            'controller'  => 'user', 
            'action'      => 'order', 
            'id'          => $order['id']
        )));
        $order['user_link'] = Pi::url($this->url('', array(
            'module'      => $module, 
            'controller'  => 'user'
        )));
        $order['index_link'] = Pi::url($this->url('', array(
            'module'      => $module, 
            'controller'  => 'index'
        )));
        // Get invoice information
        Pi::service('i18n')->load(array('module/payment', 'default'));
        $invoice = Pi::api('invoice', 'payment')->getInvoiceFromItem('shop', 'order', $order['id']);
        // Send Mail
        Pi::api('order', 'shop')->sendUserMail($order);
        Pi::api('order', 'shop')->sendAdminMail($order);
        // Set view
        $this->view()->setTemplate('checkout_finish');
        $this->view()->assign('order', $order);
        $this->view()->assign('invoice', $invoice);
    } */

    protected function setEmpty()
    {
        if (isset($_SESSION['shop']['cart'])) {
            unset($_SESSION['shop']['cart']);
        }
    }

    protected function setInvoice()
    {    
    	$invoice = array();
        $invoice['total']['price'] = 0;
        $invoice['total']['number'] = 0;
        $invoice['total']['discount'] = 0;
        $invoice['total']['shipping'] = 0;
        $invoice['total']['plan'] = 0;
        // Get cart
    	$cart = $_SESSION['shop']['cart'];
        // Set invoice product
    	foreach ($cart['product'] as $product) {
    		// Set product item
            $item = array();
            $item['id'] = $product['id'];
            $item['number'] = $product['number'];
            $item['price'] = $product['price'];
            $item['price_view'] = Pi::api('product', 'shop')->viewPrice($item['price']);
            $item['discount'] = 0;
            $item['total'] = ($product['number'] * $product['price']);
            $item['total_view'] = Pi::api('product', 'shop')->viewPrice($item['total']);
            $invoice['product'][$product['id']] = $item;
            // Set total
    		$invoice['total']['price'] = $invoice['total']['price'] + ($product['price'] * $product['number']);
    		$invoice['total']['number'] = $invoice['total']['number'] + $product['number'];
    		$invoice['total']['discount'] = $invoice['total']['discount'] + 0;
            // Update cart product
            $cart['product'][$item['id']]['total'] = $item['total'];
            $cart['product'][$item['id']]['total_view'] = $item['total_view'];
            // Set plan
            $invoice['total']['plan'] = $product['plan'];
    	}
        // Set price
        $invoice['total']['location'] = (isset($cart['invoice']['total']['location'])) ? $cart['invoice']['total']['location'] : 0;
        $invoice['total']['delivery'] = (isset($cart['invoice']['total']['delivery'])) ? $cart['invoice']['total']['delivery'] : 0;
        $invoice['total']['shipping'] = (isset($cart['invoice']['total']['shipping'])) ? intval($cart['invoice']['total']['shipping']) : 0;
        $invoice['total']['payment'] = (isset($cart['invoice']['total']['payment'])) ? $cart['invoice']['total']['payment'] : 'offline';
        $invoice['total']['location_title'] = (isset($cart['invoice']['total']['location_title'])) ? $cart['invoice']['total']['location_title'] : '';
        $invoice['total']['delivery_title'] = (isset($cart['invoice']['total']['delivery_title'])) ? $cart['invoice']['total']['delivery_title'] : '';
        $invoice['total']['payment_title'] = (isset($cart['invoice']['total']['payment_title'])) ? $cart['invoice']['total']['payment_title'] : __('Offline');
        $invoice['total']['total_price'] = intval($invoice['total']['price'] - $invoice['total']['discount']) + $invoice['total']['shipping'];
        $invoice['total']['price_view'] = Pi::api('product', 'shop')->viewPrice($invoice['total']['price']);
    	$invoice['total']['discount_view'] = Pi::api('product', 'shop')->viewPrice($invoice['total']['discount']);
        $invoice['total']['shipping_view'] = Pi::api('product', 'shop')->viewPrice($invoice['total']['shipping']);
    	$invoice['total']['total_price_view'] = Pi::api('product', 'shop')->viewPrice($invoice['total']['total_price']);
    	// Set Seaaion
    	$cart['invoice'] = $invoice;
        $_SESSION['shop']['cart'] = $cart;
        // return invoice
        return $invoice;
    }

    protected function setProduct($product)
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
        $product['total_view'] = Pi::api('product', 'shop')->viewPrice($product['total']);
        // Set session
        $cart['product'][$product['id']] = $product;
        $_SESSION['shop']['cart'] = $cart;
    }
}