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
use Module\Shop\Form\OrderForm;
use Module\Shop\Form\OrderFilter;
use Zend\Json\Json;

class CheckoutController extends IndexController
{

    /**
     * order Columns
     */
    protected $orderColumns = array(
        'id', 'uid', 'first_name', 'last_name', 'email', 'phone', 'mobile', 'company', 'address', 
        'country', 'city', 'zip_code', 'ip', 'status_order', 'status_payment', 'status_delivery', 
        'time_create', 'time_payment', 'time_delivery', 'time_finish', 'user_note', 'admin_note', 
        'number', 'product_price', 'discount_price', 'shipping_price', 'packing_price', 
        'total_price', 'paid_price', 'packing', 'delivery', 'payment_method', 'payment_adapter',
    );	

    public function indexAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Set order form
        $form = new OrderForm('order');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new OrderFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set just order fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->orderColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set cart
                $cart = $_SESSION['shop']['cart'];
                // Set values
                $values['uid'] = Pi::user()->getId();
                $values['ip'] = Pi::user()->getIp();
                $values['status_order'] = 1;
                $values['status_payment'] = 1;
                $values['status_delivery'] = 1;
                $values['time_create'] = time();
                $values['number'] = $cart['invoice']['total']['number'];
                $values['product_price'] = $cart['invoice']['total']['price'];
                $values['discount_price'] = $cart['invoice']['total']['discount'];
                $values['shipping_price'] = 0;
                $values['packing_price'] = 0;
                $values['total_price'] = $cart['invoice']['total']['total_price'];
                $values['payment_adapter'] = 'Mellat';
                // Save values to order
                $row = $this->getModel('order')->createRow();
                $row->assign($values);
                $row->save();
                // Set user info
                Pi::api('shop', 'user')->setUserInfo($values);
                // Save order basket
                foreach ($cart['invoice']['product'] as $product) {
                    $basket = $this->getModel('order_basket')->createRow();
                    $basket->order = $row->id;
                    $basket->product = $product['id'];
                    $basket->product_price = $product['price'];
                    $basket->discount_price = $product['discount'];
                    $basket->total_price = $product['total'];
                    $basket->number = $product['number'];
                    $basket->save();
                }
                // Set invoice description
                $description = array();
                foreach ($cart['product'] as $product) {
                    $item = array();
                    $item['id'] = $product['id'];
                    $item['title'] = $product['title'];
                    $item['price'] = $product['price'];
                    $item['number'] = $product['number'];
                    $item['total'] = $product['total'];
                    $description[$product['id']] = $item;
                }
                // Set order array
                $order = array();
                $order['module'] = $this->getModule();
                $order['part'] = 'order';
                $order['id'] = $row->id;
                $order['amount'] = $row->total_price;
                $order['adapter'] = $row->payment_adapter;
                $order['description'] = json_encode($description);
                // Payment module
                $result = Pi::api('payment', 'invoice')->createInvoice(
                    $order['module'], 
                    $order['part'], 
                    $order['id'], 
                    $order['amount'], 
                    $order['adapter'], 
                    $order['description']
                );
                // Check it save or not
                if ($result['status']) {
                    // unset cart
                    $this->setEmpty();
                    // Go to payment
                    $this->jump($result['invoice_url'], $result['message']);
                } else {
                    $message = __('Checkout data not saved.');
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }   
        } else {
            $message = '';
            $user = Pi::api('shop', 'user')->getUserInfo();
            $form->setData($user);
        }
        // Set view
        $this->view()->setTemplate('checkout_information');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Checkout'));
        $this->view()->assign('message', $message);
    }

    public function levelAjaxAction()
    {
        $return = array();
        return $return;
    }

    public function basketAjaxAction()
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
        	    	$oldNumber = $this->params('number');
        	    	$newNumber = $number + $oldNumber;
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

    public function cartAjaxAction()
    {
        return $_SESSION['shop']['cart']['invoice'];
    }

    public function addAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Set view
        $this->view()->setTemplate(false);
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category list
        $categoryList = Pi::api('shop', 'category')->categoryList();
        // Find product
        $product = $this->getModel('product')->find($slug, 'slug');
        $product = Pi::api('shop', 'product')->canonizeProduct($product, $categoryList);
        // Check product
        if (!$product['marketable']) {
        	$url = array('', 'module' => $module, 'controller' => 'index');
            $this->jump($url, __('The product was not marketable.'));
        } else {
            // Set total price
            $this->setProduct($product);
            // Set Invoice
            $this->setInvoice();
            // Go to cart
            $url = array('', 'module' => $module, 'controller' => 'checkout', 'action' => 'cart');
            return $this->redirect()->toRoute('', $url);
        }
    }

    public function cartAction()
    {
    	// Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Set Invoice
        $this->setInvoice();
    	$cart = $_SESSION['shop']['cart'];
    	if (empty($cart['product'])) {
        	$url = array('', 'module' => $module, 'controller' => 'index');
            $this->jump($url, __('Your cart are empty.'));
    	}
    	$this->view()->setTemplate('checkout_cart');
    	$this->view()->assign('cart', $cart);
    }

    public function emptyAction()
    {
        $this->setEmpty();
        $module = $this->params('module');
        $url = array('', 'module' => $module, 'controller' => 'index');
        $this->jump($url, __('Your cart are empty'));
    }

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
        // 
    	$cart = $_SESSION['shop']['cart'];
    	foreach ($cart['product'] as $product) {
    		// Set product item
            $item = array();
            $item['id'] = $product['id'];
            $item['number'] = $product['number'];
            $item['price'] = $product['price'];
            $item['price_view'] = Pi::api('shop', 'product')->viewPrice($item['price']);
            $item['discount'] = 0;
            $item['total'] = ($product['number'] * $product['price']);
            $item['total_view'] = Pi::api('shop', 'product')->viewPrice($item['total']);
            $invoice['product'][$product['id']] = $item;
            // Set total
    		$invoice['total']['price'] = $invoice['total']['price'] + ($product['price'] * $product['number']);
    		$invoice['total']['number'] = $invoice['total']['number'] + $product['number'];
    		$invoice['total']['discount'] = $invoice['total']['discount'] + 0;
    	}
    	// Set price
    	$invoice['total']['price_view'] = Pi::api('shop', 'product')->viewPrice($invoice['total']['price']);
    	$invoice['total']['discount_view'] = Pi::api('shop', 'product')->viewPrice($invoice['total']['discount']);
    	$invoice['total']['total_price'] = intval($invoice['total']['price_view'] - $invoice['total']['discount_view']);
    	$invoice['total']['total_price_view'] = Pi::api('shop', 'product')->viewPrice($invoice['total']['total_price']);
    	// Set Seaaion
    	$cart['invoice'] = $invoice;
    	$_SESSION['shop']['cart'] = $cart;
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
        $product['total_view'] = Pi::api('shop', 'product')->viewPrice($product['total']);
        // Set session
        $cart['product'][$product['id']] = $product;
        $_SESSION['shop']['cart'] = $cart;
    }
}