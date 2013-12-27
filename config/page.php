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
    // Admin section
    'admin' => array(
        array(
            'controller'    => 'product',
            'permission'    => 'product',
        ),
        array(
            'controller'    => 'category',
            'permission'    => 'category',
        ),
        array(
            'controller'    => 'attach',
            'permission'    => 'attach',
        ),
        array(
            'controller'    => 'order',
            'permission'    => 'order',
        ),
        array(
            'controller'    => 'checkout',
            'permission'    => 'checkout',
        ),
    ),
    // Front section
    'front' => array(
        array(
            'controller'    => 'index',
            'permission'    => 'index',
            'block'         => 1,
        ),
        array(
            'controller'    => 'category',
            'permission'    => 'category',
            'block'         => 1,
        ),
        array(
            'controller'    => 'product',
            'permission'    => 'product',
            'block'         => 1,
        ),
        array(
            'controller'    => 'tag',
            'permission'    => 'tag',
            'block'         => 1,
        ),
        array(
            'controller'    => 'user',
            'permission'    => 'user',
            'block'         => 1,
        ),
        array(
            'controller'    => 'search',
            'permission'    => 'search',
            'block'         => 1,
        ),
        array(
            'controller'    => 'checkout',
            'permission'    => 'checkout',
            'block'         => 1,
        ),
    ),
);
