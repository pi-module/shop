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

class UserController extends IndexController
{
	public function indexAction()
    {
        // User
    	$user = $this->getUser();
        // Order
    	$order = Pi::api('shop', 'order')->userOrder();
        // Set view
        $this->view()->setTemplate('user_index');
        $this->view()->assign('user', $user);
        $this->view()->assign('orders', $order);
    }

    public function orderAction()
    {
        // Check user
    	$user = $this->getUser();
        // Get info from url
        $id = $this->params('id');
        $module = $this->params('module');
        // Find order
        $order = $this->getModel('order')->find($id);
        // Check order
        if (!$order->id) {
            $url = array('', 'module' => $module, 'controller' => 'index');
            $this->jump($url, __('Order not set.'));
        }
        // Check user
        if ($order->uid != Pi::user()->getId()) {
            $url = array('', 'module' => $module, 'controller' => 'index');
            $this->jump($url, __('It not your order.'));
        }
        // canonize Order
        $order = Pi::api('shop', 'order')->canonizeOrder($order);
        $order['product'] = Pi::api('shop', 'order')->listProduct($order['id']);
        // Set view
        $this->view()->setTemplate('user_order');
        $this->view()->assign('user', $user);
        $this->view()->assign('order', $order);
    }

    public function getUser()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // find user
        $user = Pi::api('shop', 'user')->findUser();
        if (empty($user)) {
            $url = array('', 'module' => $this->params('module'), 'controller' => 'index');
            $this->jump($url, __('You did not bought any products from us.'));
        }
        // return
        return $user;
    }
}