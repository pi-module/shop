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
    	$order = Pi::api('order', 'shop')->userOrder();
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
            $this->jump($url, __('Order not set.'), 'error');
        }
        // Check user
        if ($order->uid != Pi::user()->getId()) {
            $url = array('', 'module' => $module, 'controller' => 'index');
            $this->jump($url, __('It not your order.'), 'error');
        }
        // canonize Order
        $order = Pi::api('order', 'shop')->canonizeOrder($order);
        $order['product'] = Pi::api('order', 'shop')->listProduct($order['id']);
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
        $user = Pi::api('user', 'shop')->findUser();
        if (empty($user)) {
            $url = array('', 'module' => $this->params('module'), 'controller' => 'index');
            $this->jump($url, __('You did not bought any products from us.'), 'error');
        }
        // return
        return $user;
    }
}