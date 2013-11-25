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
                // Check and save order information on db
                /* Not yet */


                // Set invoice description
                $description = array();
                foreach ($$_SESSION['shop']['cart']['product'] as $product) {
                    $item = array();
                    $item['id'] = $product['id'];
                    $item['title'] = $product['title'];
                    $item['price'] = $product['price'];
                    $item['description'] = $product['summary'];
                    $item['url'] = $product['productUrl'];
                    $item['number'] = $product['number'];
                    $item['total'] = $product['total'];
                    $description[$product['id']] = $item;
                }
                // Set order array
                $order = array();
                $order['module'] = $this->getModule();
                $order['part'] = 'product';
                $order['id'] = '1';
                $order['amount'] = $_SESSION['shop']['cart']['invoice']['total']['total_price'];
                $order['adapter'] = 'Mellat';
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
                    unset($_SESSION['shop']['cart']);
                    // Go to payment
                    $this->jump($result['invoice_url'], $result['message']);
                } else {
                    $message = __('Review data not saved. uid = ' . Pi::user()->getId());
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }   
        } else {
            $message = 'You can add new review';
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
        // Set cart
        /* $cart = $_SESSION['shop']['cart'];
        if (empty($cart['invoice'])) {
            return false;
        }
        // Set 
        if (!isset($cart['checkout'])) {
            $cart['checkout'] = array();
        }
        // Get level
        $level = $this->params('level', 'info');
        // Set level action
        switch ($level) {
            case 'saveInfo':

                break;
            
            case 'info':
            default:
                // Set order form
                $form = new OrderForm('review');
                $form->setAttribute('action', $this->url('', array('action' => 'index', 'level' => 'saveInfo')));
                // Set return
                $return['type'] = 'form';
                $return['content'] = $form;
                break;
        }
        // Set session
        $cart['checkout']['level'] = $level;
        $_SESSION['shop']['cart'] = $cart;
        // return */
        return $return;
    }

    public function basketAjaxAction()
    {
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
        if (isset($_SESSION['shop']['cart'])) {
            unset($_SESSION['shop']['cart']);
        }
        $module = $this->params('module');
        $url = array('', 'module' => $module, 'controller' => 'index');
        $this->jump($url, __('Your cart are empty'));
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