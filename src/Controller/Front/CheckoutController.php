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

class CheckoutController extends IndexController
{
	public function indexAction()
    {
    	$module = $this->params('module');
    	$url = array('', 'module' => $module, 'controller' => 'index');
    	$this->jump($url, __('Please select action.'));
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

    public function ajaxAction()
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
        	    		$cart['product'][$product]['number'] = $newNumber;
        	    		$return['actionNumber'] = $newNumber;
        	    		$return['message'] = __('Update number');
                        $return['ajaxStatus'] = 1;
                        $return['actionStatus'] = 1;
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

    public function addAction()
    {
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
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
            // Update cart session
            $cart = $_SESSION['shop']['cart'];
            $product['number'] = 1;
            // Set total price
            $product = $this->totalPriceProduct($product);
            $cart['product'][$product['id']] = $product;
            $_SESSION['shop']['cart'] = $cart;
            // Set view
            $this->view()->setTemplate(false);
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
    	//unset($_SESSION['shop']['cart']);
    	$cart = $_SESSION['shop']['cart'];
    	if (empty($cart['product'])) {
        	$url = array('', 'module' => $module, 'controller' => 'index');
            $this->jump($url, __('Your cart are empty.'));
    	}
    	$this->view()->setTemplate('checkout_cart');
    	$this->view()->assign('cart', $cart);
    }

    protected function setInvoice()
    {
    	$invoice = array();
    	$cart = $_SESSION['shop']['cart'];
    	foreach ($cart['product'] as $product) {
    		$invoice['product'][$product['id']] = array(
    			'id' => $product['id'],
    			'number' => $product['number'],
    			'price' => $product['price'],
    			'discount' => 0,
    		);
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

    protected function totalPriceProduct($product)
    {
    	$product['total'] = intval(($product['price'] * $product['number']));
    	$product['total_view'] = Pi::api('shop', 'product')->viewPrice($product['total']);
    	return $product;
    }
}