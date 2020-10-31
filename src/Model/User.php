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

namespace Module\Shop\Model;

use Pi\Application\Model\Model;

class User extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns
        = [
            'id',
            'uid',
            'order_active',
            'product_count',
            'product_fee',
            'time_last_order',
            'products',
        ];
}
