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
return [
    // route name
    'shop' => [
        'name'    => 'shop',
        'type'    => 'Module\Shop\Route\Shop',
        'options' => [
            'route'    => '/shop',
            'defaults' => [
                'module'     => 'shop',
                'controller' => 'index',
                'action'     => 'index',
            ],
        ],
    ],
];