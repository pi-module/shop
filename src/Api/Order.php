<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Laminas\Math\Rand;

/*
 * Pi::api('order', 'shop')->getProductDetails($id);
 * Pi::api('order', 'shop')->postPaymentUpdate($order, $basket);
 */

class Order extends AbstractApi
{
    /*
     * Start Order module needed functions
     */
    public function checkProduct($id, $type = null)
    {
        $product = Pi::model('product', 'shop')->find($id, 'id');
        if (empty($product) || $product['status'] != 1) {
            return false;
        }
        return true;
    }

    public function getInstallmentDueDate($cart = [], $composition = [100])
    {
        return null;
    }

    public function getInstallmentComposition($extra = [])
    {
        return [100];
    }

    public function getProductDetails($id)
    {
        return Pi::api('product', 'shop')->getProductOrder($id);
    }

    public function postPaymentUpdate($order, $detail)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Update products
        if (!empty($detail)) {
            foreach ($detail as $single) {

                // Get product
                $product = Pi::api('product', 'shop')->getProductLight(intval($single['product']));

                // Update sold
                Pi::model('product', $this->getModule())->update(
                    ['sold' => ($product['sold'] + $single['number'])],
                    ['id' => $product['id']]
                );

                // Stock method
                switch ($config['order_stock']) {
                    case 'never':
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
        $url = Pi::url(
            Pi::service('url')->assemble(
                'order', [
                    'module'     => 'order',
                    'controller' => 'detail',
                    'action'     => 'index',
                    'id'         => $order['id'],
                ]
            )
        );

        return $url;
    }

    public function createExtraDetailForProduct($values)
    {
        return json_encode(
            [
                'item' => $values['module_item'],
            ]
        );
    }

    public function getExtraFieldsFormForOrder()
    {
        return [];
    }

    public function isAlwaysAvailable($order)
    {
        return [
            'status' => 1,
        ];
    }

    public function showInInvoice($order, $product)
    {
        return true;
    }

    public function postCancelUpdate($order, $detail)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Update products
        if (!empty($detail)) {
            foreach ($detail as $single) {

                // Get product
                $product = Pi::api('product', 'shop')->getProductLight(intval($single['product']));

                // Get user
                $user = Pi::api('user', 'shop')->get($order['uid']);

                // CLean up product
                $productList = [];
                foreach ($user['products'] as $productSingle) {
                    if (intval($productSingle) != $product['id']) {
                        $productList[$productSingle] = $productSingle;
                    }
                }

                //
                $params = [
                    'uid'           => $order['uid'],
                    'order_active'  => 1,
                    'product_count' => intval($user['product_count'] - 1) > 0 ? ($user['product_count'] - 1) : 0,
                    'product_fee'   => ($user['product_fee'] - $product['price']) > 0 ? $user['product_fee'] - $product['price'] : 0,
                    'products'      => $productList,
                ];

                // Update user
                Pi::api('user', 'shop')->update($params);

                return true;
            }
        }
    }
}