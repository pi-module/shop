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
 * Pi::api('user', 'shop')->update($params);
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

    public function update($params)
    {
        $update = [];

        if (isset($params['order_active']) && !empty($params['order_active'])) {
            $update['order_active'] = $params['order_active'];
        }

        if (isset($params['product_count']) && !empty($params['product_count'])) {
            $update['product_count'] = $params['product_count'];
        }

        if (isset($params['product_fee']) && !empty($params['product_fee'])) {
            $update['product_fee'] = $params['product_fee'];
        }

        if (isset($params['time_last_order']) && !empty($params['time_last_order'])) {
            $update['time_last_order'] = $params['time_last_order'];
        }

        if (isset($params['products']) && !empty($params['products'])) {
            $update['products'] = json_encode($params['products']);
        }

        // Update sold
        if (isset($params['uid']) && intval($params['uid']) > 0 && !empty($update)) {
            Pi::model('user', $this->getModule())->update(
                [
                    $update
                ],
                [
                    'uid' => intval($params['uid'])
                ]
            );
        }
    }
}
