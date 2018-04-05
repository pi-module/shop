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
    public function checkProduct($id, $type = null)
    {
        $product = Pi::model('product', 'shop')->find($id, 'id');
        if (empty($product) || $product['status'] != 1) {
            return false;
        }
        return true;
    }
    
    public function getProductDetails($id)
    {
        return Pi::api('product', 'shop')->getProductOrder($id);
    }

    public function postPaymentUpdate($order, $basket)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Update products
        if (!empty($basket)) {
            foreach ($basket as $single) {
                // Get product
                $product = Pi::api('product', 'shop')->getProductLight($single['product']);
                // Update sold
                Pi::model('product', $this->getModule())->update(
                    ['sold' => ($product['sold'] + $single['number'])],
                    ['id' => $product['id']]
                );
                // Stock method
                switch ($config['order_stock']) {
                    case 'never':
                        break;

                    case 'manual':
                        break;

                    case 'product':
                        // Update stock
                        Pi::model('product', $this->getModule())->update(
                            ['stock' => ($product['stock'] - $single['number'])],
                            ['id' => $product['id']]
                        );
                        break;

                    case 'property':
                        // Check order extra
                        if (isset($single['extra']['product']) && !empty($single['extra']['product'])) {
                            foreach ($single['extra']['product'] as $property) {
                                // Get property value
                                $propertyValue = Pi::api('property', 'shop')->getPropertyValue($property['unique_key']);
                                // Update stock
                                Pi::model('property_value', $this->getModule())->update(
                                    ['stock' => ($propertyValue['stock'] - $single['number'])],
                                    ['unique_key' => $propertyValue['unique_key']]
                                );
                            }
                        }
                        break;
                }
            }
        }
        // Set back url
        /* $url = Pi::url(Pi::service('url')->assemble('shop', array(
            'module' => $this->getModule(),
            'controller' => 'cart',
            'action' => 'finish',
            'id' => $order['id'],
        ))); */

        $url = Pi::url(Pi::service('url')->assemble('order', [
            'module'     => 'order',
            'controller' => 'detail',
            'action'     => 'index',
            'id'         => $order['id'],
        ]));

        return $url;
    }

    public function createExtraDetailForProduct($values) 
    {
        return json_encode(
            array(
               'item' => $values['module_item'],
            )
        );   
    }
    public function getExtraFieldsFormForOrder()
    {
        return array();
    }
    
}