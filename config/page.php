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
    // Admin section
    'admin' => [
        [
            'label'      => _a('Product'),
            'controller' => 'product',
            'permission' => 'product',
        ],
        [
            'label'      => _a('Category'),
            'controller' => 'category',
            'permission' => 'category',
        ],
        [
            'label'      => _a('Attribute'),
            'controller' => 'attribute',
            'permission' => 'attribute',
        ],
        [
            'label'      => _a('Attribute position'),
            'controller' => 'position',
            'permission' => 'position',
        ],
        [
            'label'      => _a('Order property'),
            'controller' => 'property',
            'permission' => 'property',
        ],
        [
            'label'      => _a('Attach'),
            'controller' => 'attach',
            'permission' => 'attach',
        ],
        [
            'label'      => _a('Discount'),
            'controller' => 'discount',
            'permission' => 'discount',
        ],
        [
            'label'      => _a('Sale'),
            'controller' => 'sale',
            'permission' => 'sale',
        ],
        [
            'label'      => _a('Promotion'),
            'controller' => 'promotion',
            'permission' => 'promotion',
        ],
        [
            'label'      => _a('Logs'),
            'controller' => 'log',
            'permission' => 'log',
        ],
        [
            'label'      => _a('Tools'),
            'controller' => 'tools',
            'permission' => 'tools',
        ],
        [
            'title'      => _a('Json output'),
            'controller' => 'json',
            'permission' => 'json',
        ],
        [
            'title'      => _a('Price systems'),
            'controller' => 'price',
            'permission' => 'price',
        ],
    ],
    // Front section
    'front' => [
        [
            'title'      => _a('Index page'),
            'controller' => 'index',
            'permission' => 'public',
            'block'      => 1,
        ],
        [
            'label'      => _a('Category'),
            'controller' => 'category',
            'permission' => 'public',
            'block'      => 1,
        ],
        [
            'label'      => _a('Product'),
            'controller' => 'product',
            'permission' => 'public',
            'block'      => 1,
        ],

        [
            'label'      => _a('Tags'),
            'controller' => 'tag',
            'permission' => 'public',
            'block'      => 1,
        ],
        [
            'label'      => _a('Checkout'),
            'controller' => 'checkout',
            'permission' => 'checkout',
            'block'      => 1,
        ],
        [
            'label'      => _a('Category list'),
            'controller' => 'category',
            'action'     => 'list',
            'permission' => 'public',
            'block'      => 1,
        ],
        [
            'label'      => _a('Tag list'),
            'controller' => 'tag',
            'action'     => 'list',
            'permission' => 'public',
            'block'      => 1,
        ],
        [
            'label'      => _a('Json output'),
            'controller' => 'json',
            'permission' => 'public',
            'block'      => 0,
        ],
    ],
];
