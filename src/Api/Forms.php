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
 * Pi::api('forms', 'shop')->postReview($params);
 */

class Forms extends AbstractApi
{
    public function postReview($params)
    {
        // Get user
        $user = Pi::api('user', 'shop')->get($params['uid']);

        // Update
        if ($user['order_active'] != 1) {
            Pi::model('user', $this->getModule())->update(
                [
                    'order_active' => 1,
                ],
                [
                    'uid' => $user['uid'],
                ]
            );
        }
    }
}
