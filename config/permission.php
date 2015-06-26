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
    // Front section
    'front' => array(
        'public' => array(
            'title' => _a('Global public resource'),
            'access' => array(
                'guest',
                'member',
            ),
        ),
        'search' => array(
            'title' => _a('Search'),
            'access' => array(
                'guest',
                'member',
            ),
        ),
        'user' => array(
            'title' => _a('User'),
            'access' => array(
                'member',
            ),
        ),
        'checkout' => array(
            'title' => _a('Checkout'),
            'access' => array(
                'member',
            ),
        ),
    ),
    // Admin section
    'admin' => array(
        'product' => array(
            'title' => _a('Product'),
            'access' => array(//'admin',
            ),
        ),
        'category' => array(
            'title' => _a('Category'),
            'access' => array(//'admin',
            ),
        ),
        'attribute' => array(
            'title' => _a('Attribute'),
            'access' => array(//'admin',
            ),
        ),
        'position' => array(
            'title' => _a('Attribute position'),
            'access' => array(//'admin',
            ),
        ),
        'property' => array(
            'title' => _a('Order property'),
            'access' => array(//'admin',
            ),
        ),
        'discount' => array(
            'title' => _a('Discount'),
            'access' => array(//'admin',
            ),
        ),
        'attach' => array(
            'title' => _a('Attach'),
            'access' => array(//'admin',
            ),
        ),
        'special' => array(
            'title' => _a('Special products'),
            'access' => array(//'admin',
            ),
        ),
        'log' => array(
            'title' => _a('Logs'),
            'access' => array(//'admin',
            ),
        ),
        'tools' => array(
            'title' => _a('Tools'),
            'access' => array(//'admin',
            ),
        ),
    ),
);