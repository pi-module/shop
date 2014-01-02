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
return array(
    // route name
    'shop'  => array(
        'name'      => 'shop',
        'type'      => 'Module\Shop\Route\Shop',
        'options'   => array(
            'route'     => '/shop',
            'defaults'  => array(
                'module'        => 'shop',
                'controller'    => 'index',
                'action'        => 'index'
            )
        ),
    )
);