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
use Pi\Application\AbstractApi;
use Zend\Json\Json;

/*
 * Pi::api('shop', 'order')->updatePayment();
 * Pi::api('shop', 'order')->canonizeOrder($order);
 * Pi::api('shop', 'order')->orderStatus($status_order);
 * Pi::api('shop', 'order')->paymentStatus($status_payment);
 * Pi::api('shop', 'order')->deliveryStatus($status_delivery);
 */

class Order extends AbstractApi
{
    public function updatePayment($item, $amount, $adapter)
    {
        $row = Pi::model('order', $this->getModule())->find($item);
        $row->paid_price = $amount;
        $row->time_payment = time();
        $row->payment_adapter = $adapter;
        $row->status_payment = 2;
        $row->save();
        // Set back url
        return Pi::service('url')->assemble('shop', array(
            'module'        => $this->getModule(),
            'controller'    => 'checkout',
            'action'        => 'finish',
            'id'            => $row->id,
        ));
    }

    public function findOrder($id)
    {
        $row = Pi::model('order', $this->getModule())->find($id);
        if (is_object($row)) {
        $order = $row->toArray();
        } else {
        $order = array();
        }
        return $order;
    }

    public function setOrderInfo($values)
    {
        if ($values['id']) {
            $row = Pi::model('order', $this->getModule())->find($values['id']);
        } else {
            $row = Pi::model('order', $this->getModule())->createRow();
        }
        // Set info
        /* $row->user = $uid;
        $row->first_name = $values['first_name'];
        $row->last_name = $values['last_name'];
        $row->email = $values['email'];
        $row->phone = $values['phone'];
        $row->mobile = $values['mobile'];
        $row->company = $values['company'];
        $row->address = $values['address'];
        $row->country = $values['country'];
        $row->city = $values['city'];
        $row->zip_code = $values['zip_code'];
        $row->number = $values['number']; */
        $row->save();
    }

    public function getOrderInfo($id)
    {
        $user = $this->findOrder($id);
        /* $values['first_name'] = ($user['first_name']) ? $user['first_name'] : '';
        $values['last_name'] = ($user['last_name']) ? $user['last_name'] : '';
        $values['email'] = ($user['email']) ? $user['email'] : '';
        $values['phone'] = ($user['phone']) ? $user['phone'] : '';
        $values['mobile'] = ($user['mobile']) ? $user['mobile'] : '';
        $values['company'] = ($user['company']) ? $user['company'] : '';
        $values['address'] = ($user['address']) ? $user['address'] : '';
        $values['country'] = ($user['country']) ? $user['country'] : '';
        $values['city'] = ($user['city']) ? $user['city'] : '';
        $values['zip_code'] = ($user['zip_code']) ? $user['zip_code'] : ''; */
        return $values;
    }

    public function orderStatus($status_order)
    {
        $return = array();
        switch ($status_order) {
            case '1':
                $return['orderClass'] = 'btn-warning';
                $return['orderTitle'] = __('Not processed');
                break;

            case '2':
                $return['orderClass'] = 'btn-success';
                $return['orderTitle'] = __('Orders validated');
                break;

            case '3':
                $return['orderClass'] = 'btn-danger';
                $return['orderTitle'] = __('Orders pending');
                break;

            case '4':
                $return['orderClass'] = 'btn-danger';
                $return['orderTitle'] = __('Orders failed');
                break;

            case '5':
                $return['orderClass'] = 'btn-danger';
                $return['orderTitle'] = __('Orders cancelled');
                break;

            case '6':
                $return['orderClass'] = 'btn-danger';
                $return['orderTitle'] = __('Fraudulent orders');
                break;
        }
        return $return;
    }

    public function paymentStatus($status_payment)
    {
        $return = array();
        switch ($status_payment) {
            case '1':
                $return['paymentClass'] = 'btn-warning';
                $return['paymentTitle'] = __('UnPaid');
                break;

            case '2':
                $return['paymentClass'] = 'btn-success';
                $return['paymentTitle'] = __('Paid');
                break;
        }
        return $return;
    }

    public function deliveryStatus($status_delivery)
    {
        $return = array();
        switch ($status_delivery) {
            case '1':
                $return['deliveryClass'] = 'btn-warning';
                $return['deliveryTitle'] = __('Not processed');
                break;

            case '2':
                $return['deliveryClass'] = 'btn-info';
                $return['deliveryTitle'] = __('Packed');
                break;

            case '3':
                $return['deliveryClass'] = 'btn-info';
                $return['deliveryTitle'] = __('Posted');
                break;

            case '4':
                $return['deliveryClass'] = 'btn-success';
                $return['deliveryTitle'] = __('Delivered');
                break;

            case '5':
                $return['deliveryClass'] = 'btn-danger';
                $return['deliveryTitle'] = __('Back eaten');
                break; 
        }
        return $return;
    }

    public function canonizeOrder($order)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // boject to array
        $order = $order->toArray();
        // Set time_create_view
        $order['time_create_view'] = _date($order['time_create']);
        // Set time_payment_view
        $order['time_payment_view'] = ($order['time_payment']) ? _date($order['time_payment']) : __('Not Paid');
        // Set time_delivery_view
        $order['time_delivery_view'] = ($order['time_delivery']) ? _date($order['time_delivery']) : __('Not Delivery');
        // Set time_finish_view
        $order['time_finish_view'] = ($order['time_finish']) ? _date($order['time_finish']) : __('Not Finish');
        // Set product_price_view
        $order['product_price_view'] = Pi::api('shop', 'product')->viewPrice($order['product_price']);
        // Set discount_price_view
        $order['discount_price_view'] = Pi::api('shop', 'product')->viewPrice($order['discount_price']);
        // Set shipping_price_view
        $order['shipping_price_view'] = Pi::api('shop', 'product')->viewPrice($order['shipping_price']);
        // Set packing_price_view
        $order['packing_price_view'] = Pi::api('shop', 'product')->viewPrice($order['packing_price']);
        // Set total_price_view
        $order['total_price_view'] = Pi::api('shop', 'product')->viewPrice($order['total_price']);
        // Set paid_price_view
        $order['paid_price_view'] = Pi::api('shop', 'product')->viewPrice($order['paid_price']);
        // Set user
        $order['user'] = Pi::user()->get($order['uid'], array('id', 'identity', 'name', 'email'));
        // Set url_update_order
        $order['url_update_order'] = Pi::service('url')->assemble('', array(
            'controller'    => 'order',
            'action'        => 'updateOrder',
            'id'            => $order['id'],
        ));
        // Set url_update_payment
        $order['url_update_payment'] = Pi::service('url')->assemble('', array(
            'controller'    => 'order',
            'action'        => 'updatePayment',
            'id'            => $order['id'],
        ));
        // Set url_update_delivery
        $order['url_update_delivery'] = Pi::service('url')->assemble('', array(
            'controller'    => 'order',
            'action'        => 'updateDelivery',
            'id'            => $order['id'],
        ));
        // Set url_edit
        $order['url_edit'] = Pi::service('url')->assemble('', array(
            'controller'    => 'order',
            'action'        => 'edit',
            'id'            => $order['id'],
        ));
        // Set url_print
        $order['url_print'] = Pi::service('url')->assemble('', array(
            'controller'    => 'order',
            'action'        => 'print',
            'id'            => $order['id'],
        ));
        // Set url_view
        $order['url_view'] = Pi::service('url')->assemble('', array(
            'controller'    => 'order',
            'action'        => 'view',
            'id'            => $order['id'],
        ));
        // Status order
        $status_order = $this->orderStatus($order['status_order']);
        $order['orderClass'] = $status_order['orderClass'];
        $order['orderTitle'] = $status_order['orderTitle'];
        // Status payment
        $status_payment = $this->paymentStatus($order['status_payment']);
        $order['paymentClass'] = $status_payment['paymentClass'];
        $order['paymentTitle'] = $status_payment['paymentTitle'];
        // Status delivery
        $status_delivery = $this->deliveryStatus($order['status_delivery']);
        $order['deliveryClass'] = $status_delivery['deliveryClass'];
        $order['deliveryTitle'] = $status_delivery['deliveryTitle'];
        // return order
        return $order; 
    }
}