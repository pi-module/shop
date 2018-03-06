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

namespace Module\Shop\Model;

use Pi\Application\Model\Model;

class Promotion extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns = [
        'id',
        'title',
        'code',
        'type',
        'percent',
        'percent_partner',
        'price',
        'price_partner',
        'time_publish',
        'time_expire',
        'status',
        'used',
        'partner',
    ];
}