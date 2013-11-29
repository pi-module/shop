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
 */

class Order extends AbstractApi
{
    public function updatePayment($item, $amount, $adapter)
    {
        $row = Pi::model('order', $this->getModule())->find($item);
        $row->paid_price = $amount;
        $row->time_payment = time();
        $row->payment_adapter = $adapter;
        $row->status = 1;
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
}