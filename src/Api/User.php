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

/*
 * Pi::api('user', 'shop')->get($parameter, $type);
 * Pi::api('user', 'shop')->add($params);
 */

class User extends AbstractApi
{
    public function get($parameter, $type = 'uid')
    {
        $user = Pi::model('user', $this->getModule())->find($parameter, $type);
        if (!$user || empty($user)) {
            $user = Pi::api('user', 'shop')->add(['uid' => $parameter]);
        } else {
            $user             = $user->toArray();
            $user['products'] = (empty($user['products'])) ? [] : json_decode($user['products'], true);
        }
        return $user;
    }

    public function add($params = [])
    {
        $row      = Pi::model('user', $this->getModule())->createRow();
        $row->uid = isset($params['uid']) ? $params['uid'] : Pi::user()->getId();
        $row->save();

        return $row->toArray();
    }

    public function checkProducts($products, $user = [])
    {
        // Set result
        $result = [
            'allow'  => [],
            'denied' => [],
        ];

        // Check user
        if (empty($user)) {
            $uid  = Pi::user()->getId();
            $user = $this->get($uid);
        }

        // Check list is array
        $products = is_array($products) ? $products : [$products => $products];

        // Set products to allow/denied list
        foreach ($products as $product) {

            if (empty($user['products'])) {
                $result['allow'][$product] = $product;
            } else {
                if (in_array($product, $user['products'])) {
                    $result['denied'][$product] = $product;
                } else {
                    $result['allow'][$product] = $product;
                }
            }
        }

        return $result;
    }
}
