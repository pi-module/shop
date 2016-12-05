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
use Zend\Json\Json;
use Zend\Math\Rand;

/*
 * Pi::api('serial', 'shop')->checkSerial($serial);
 * Pi::api('serial', 'shop')->createSerial($product);
 */

class Serial extends AbstractApi
{
    public function checkSerial($serial)
    {
        $result = array(
            'status' => 0,
            'message' => '',
            'product' => array(),
            'serial' => array(),
        );
        $serial = Pi::model('serial', $this->getModule())->find($serial, 'serial_number');

        // Check
        if (!empty($serial)) {
            // Get product
            $result['product'] = Pi::api('product', 'shop')->getProductLight($serial->product);
            // Check serial check before or not
            if ($serial['status'] == 0) {
                $result['status'] = 1;
                $result['message'] = __('This serial number is true and joined to your product');
                // Update serial row
                $serial->status = 1;
                $serial->check_time = time();
                $serial->check_uid = Pi::user()->getId();
                $serial->check_ip = Pi::user()->getIp();
                $serial->save();
            } else {
                $result['status'] = 2;
                $result['message'] = __('This serial number checked before by another user, please call seller and ask about your product');
            }
            // Set serial
            $result['serial'] = $serial->toArray();
        } else {
            $result['status'] = 3;
            $result['message'] = __('This serial number not fount on our system ! please check you input true serial number, if is true please call seller and ask about your product');
        }

        return $result;
    }

    public function createSerial($product)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Generate serial key without 0 o
        switch ($config['serial_role_type']) {
            case 1:
                $serial = '123456789';
                break;

            case 2:
                $serial = 'abcdefghijklmnpqrstuvwxyz123456789';
                break;

            case 3:
                $serial = 'ABCDEFGHIJKLMNPQRSTUVWXYZ123456789';
                break;

            case 4:
                $serial = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz123456789';
                break;
        }
        // Save on DB
        for ($count = 1; $count <= $config['serial_count']; $count++) {
            $row = Pi::model('serial', $this->getModule())->createRow();
            $row->product = $product;
            $row->serial_number = sprintf($config['serial_role'], $product, Rand::getString(12, $serial, true));
            $row->time_create = time();
            $row->status = 0;
            $row->save();
        }
    }
}