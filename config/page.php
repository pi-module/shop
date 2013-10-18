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
            'controller'    => 'extra',
            'permission'    => 'extra',
        ),
        array(
            'controller'    => 'attach',
            'permission'    => 'attach',
        ),
        array(
            'controller'    => 'spotlight',
            'permission'    => 'spotlight',
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
);
