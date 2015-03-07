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
use Zend\Math\Rand;

/*
 * Pi::api('order', 'shop')->getProductDetails($id);
 * Pi::api('order', 'shop')->postPaymentUpdate($order, $basket);
 */

class Order extends AbstractApi
{
    public function getProductDetails($id)
    {
        return Pi::api('product', 'shop')->getProductOrder($id);
    }

    public function postPaymentUpdate($order, $basket)
    {
        // Update products

        /* TODO */

        // Send Mail
        $this->sendUserMail($order);
        $this->sendAdminMail($order);
        // Set back url
        return Pi::url(Pi::service('url')->assemble('shop', array(
            'module'        => $this->getModule(),
            'controller'    => 'cart',
            'action'        => 'finish',
            'id'            => $order['id'],
        )));
    }

    public function sendUserMail($order)
    {
        // Set user mail
        $to = array(
            $order['email'] => sprintf('%s %s',$order['first_name'], $order['last_name']),
        );
        // Set template
        $data = Pi::service('mail')->template('order-user', $order);
        // Set message
        $message = Pi::service('mail')->message($data['subject'], $data['body'], $data['format']);
        $message->addTo($to);
        $message->setEncoding("UTF-8");
        // Send mail
        Pi::service('mail')->send($message);
    }

    public function sendAdminMail($order)
    {
        // Set system mail
        $to = array(
            Pi::config('adminmail'  => Pi::config('adminname'),
        );
        // Get config and set mail list
        $config = Pi::service('registry')->config->read($this->getModule());
        $mails = $config['order_mail'];
        $mails = (empty($mails)) ? '' : explode('|', $mails);
        if (!empty($mails)) {
            foreach ($mails as $mail) {
                $to[$mail] = $mail;
            }
        }
        // Set template
        $data = Pi::service('mail')->template('order-admin', $order);
        // Set message
        $message = Pi::service('mail')->message($data['subject'], $data['body'], $data['format']);
        $message->addTo($to);
        $message->setEncoding("UTF-8");
        // Send mail
        Pi::service('mail')->send($message);
    }
}