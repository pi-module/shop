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
            $user = $user->toArray();
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
}
