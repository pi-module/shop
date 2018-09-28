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
return [
    // Front section
    'front' => [
        'public'   => [
            'title'  => _a('Global public resource'),
            'access' => [
                'guest',
                'member',
            ],
        ],
        'checkout' => [
            'title'  => _a('Checkout'),
            'access' => [
                'member',
            ],
        ],
    ],
    // Admin section
    'admin' => [
        'product'   => [
            'title'  => _a('Product'),
            'access' => [//'admin',
            ],
        ],
        'category'  => [
            'title'  => _a('Category'),
            'access' => [//'admin',
            ],
        ],
        'attribute' => [
            'title'  => _a('Attribute'),
            'access' => [//'admin',
            ],
        ],
        'position'  => [
            'title'  => _a('Attribute position'),
            'access' => [//'admin',
            ],
        ],
        'property'  => [
            'title'  => _a('Order property'),
            'access' => [//'admin',
            ],
        ],
        'attach'    => [
            'title'  => _a('Attach'),
            'access' => [//'admin',
            ],
        ],
        'discount'  => [
            'title'  => _a('Discount'),
            'access' => [//'admin',
            ],
        ],
        'sale'      => [
            'title'  => _a('Sale'),
            'access' => [//'admin',
            ],
        ],
        'promotion' => [
            'title'  => _a('Promotion'),
            'access' => [//'admin',
            ],
        ],
        'log'       => [
            'title'  => _a('Logs'),
            'access' => [//'admin',
            ],
        ],
        'tools'     => [
            'title'  => _a('Tools'),
            'access' => [//'admin',
            ],
        ],
        'json'      => [
            'title'  => _a('Json'),
            'access' => [//'admin',
            ],
        ],
        'price'     => [
            'title'  => _a('Price systems'),
            'access' => [//'admin',
            ],
        ],
    ],
];