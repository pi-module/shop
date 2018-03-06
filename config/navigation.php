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
    'front' => [
        'category' => [
            'label'      => _a('Category list'),
            'permission' => [
                'resource' => 'public',
            ],
            'route'      => 'shop',
            'module'     => 'shop',
            'controller' => 'category',
        ],

        'tag' => [
            'label'      => _a('Tag list'),
            'permission' => [
                'resource' => 'public',
            ],
            'route'      => 'shop',
            'module'     => 'shop',
            'controller' => 'tag',
        ],
    ],
    'admin' => [
        'product' => [
            'label'      => _a('Product'),
            'permission' => [
                'resource' => 'product',
            ],
            'route'      => 'admin',
            'module'     => 'shop',
            'controller' => 'product',
            'action'     => 'index',

            'pages' => [
                'product' => [
                    'label'      => _a('Product'),
                    'permission' => [
                        'resource' => 'product',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'product',
                    'action'     => 'index',
                ],
                'update'  => [
                    'label'      => _a('New product'),
                    'permission' => [
                        'resource' => 'product',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'product',
                    'action'     => 'update',
                ],
                'csv'     => [
                    'label'      => _a('Export products'),
                    'permission' => [
                        'resource' => 'product',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'product',
                    'action'     => 'export',
                ],
            ],
        ],

        'category' => [
            'label'      => _a('Category'),
            'permission' => [
                'resource' => 'category',
            ],
            'route'      => 'admin',
            'module'     => 'shop',
            'controller' => 'category',
            'action'     => 'index',
            'params'     => [
                'type' => 'category',
            ],

            'pages' => [
                'category'        => [
                    'label'      => _a('Category'),
                    'permission' => [
                        'resource' => 'category',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'category',
                    'action'     => 'index',
                    'params'     => [
                        'type' => 'category',
                    ],
                ],
                'brand'           => [
                    'label'      => _a('Brand'),
                    'permission' => [
                        'resource' => 'category',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'category',
                    'action'     => 'index',
                    'params'     => [
                        'type' => 'brand',
                    ],
                ],
                'update-category' => [
                    'label'      => _a('New category'),
                    'permission' => [
                        'resource' => 'category',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'category',
                    'action'     => 'update',
                    'params'     => [
                        'type' => 'category',
                    ],
                ],
                'update-brand'    => [
                    'label'      => _a('New brand'),
                    'permission' => [
                        'resource' => 'category',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'category',
                    'action'     => 'update',
                    'params'     => [
                        'type' => 'brand',
                    ],
                ],
                'sync'            => [
                    'label'      => _a('Sync category'),
                    'permission' => [
                        'resource' => 'category',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'category',
                    'action'     => 'sync',
                ],
                'merge'           => [
                    'label'      => _a('Merge category'),
                    'permission' => [
                        'resource' => 'category',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'category',
                    'action'     => 'merge',
                ],
            ],
        ],

        'attribute' => [
            'label'      => _a('Attribute'),
            'permission' => [
                'resource' => 'attribute',
            ],
            'route'      => 'admin',
            'module'     => 'shop',
            'controller' => 'attribute',
            'action'     => 'index',

            'pages' => [
                'attribute' => [
                    'label'      => _a('Attribute'),
                    'permission' => [
                        'resource' => 'attribute',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'attribute',
                    'action'     => 'index',
                ],
                'position'  => [
                    'label'      => _a('Attribute position'),
                    'permission' => [
                        'resource' => 'position',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'position',
                    'action'     => 'index',
                ],
            ],
        ],

        'property' => [
            'label'      => _a('Order property'),
            'permission' => [
                'resource' => 'property',
            ],
            'route'      => 'admin',
            'module'     => 'shop',
            'controller' => 'property',
            'action'     => 'index',
        ],

        'attach' => [
            'label'      => _a('Attach'),
            'permission' => [
                'resource' => 'attach',
            ],
            'route'      => 'admin',
            'module'     => 'shop',
            'controller' => 'attach',
            'action'     => 'index',
        ],

        'discount-system' => [
            'label'      => _a('Discount systems'),
            'permission' => [
                'resource' => 'discount',
            ],
            'route'      => 'admin',
            'module'     => 'shop',
            'controller' => 'discount',
            'action'     => 'index',
            'pages'      => [
                'discount'  => [
                    'label'      => _a('Discount'),
                    'permission' => [
                        'resource' => 'discount',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'discount',
                    'action'     => 'index',
                ],
                'sale'      => [
                    'label'      => _a('Sale'),
                    'permission' => [
                        'resource' => 'sale',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'sale',
                    'action'     => 'index',
                ],
                'promotion' => [
                    'label'      => _a('Promotion'),
                    'permission' => [
                        'resource' => 'promotion',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'promotion',
                    'action'     => 'index',
                ],
            ],
        ],

        'price' => [
            'label'      => _a('Price systems'),
            'permission' => [
                'resource' => 'price',
            ],
            'route'      => 'admin',
            'module'     => 'shop',
            'controller' => 'price',
            'action'     => 'index',
            'pages'      => [
                'index'  => [
                    'label'      => _a('Price systems'),
                    'permission' => [
                        'resource' => 'price',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'price',
                    'action'     => 'index',
                ],
                'update' => [
                    'label'      => _a('Price update'),
                    'permission' => [
                        'resource' => 'price',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'price',
                    'action'     => 'update',
                ],
                'csv'    => [
                    'label'      => _a('Price update from CSV'),
                    'permission' => [
                        'resource' => 'price',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'price',
                    'action'     => 'csv',
                ],
                'sync'   => [
                    'label'      => _a('Price sync'),
                    'permission' => [
                        'resource' => 'price',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'price',
                    'action'     => 'sync',
                ],
                'log'    => [
                    'label'      => _a('Price log'),
                    'permission' => [
                        'resource' => 'price',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'price',
                    'action'     => 'log',
                ],
            ],
        ],

        'tools' => [
            'label'      => _a('Tools'),
            'permission' => [
                'resource' => 'tools',
            ],
            'route'      => 'admin',
            'module'     => 'shop',
            'controller' => 'tools',
            'action'     => 'index',
            'pages'      => [
                'tools'   => [
                    'label'      => _a('Tools'),
                    'permission' => [
                        'resource' => 'tools',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'tools',
                    'action'     => 'index',
                ],
                'sitemap' => [
                    'label'      => _a('Sitemap'),
                    'permission' => [
                        'resource' => 'tools',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'tools',
                    'action'     => 'sitemap',
                ],
                'log'     => [
                    'label'      => _a('Logs'),
                    'permission' => [
                        'resource' => 'log',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'log',
                    'action'     => 'index',
                ],
                'json'    => [
                    'label'      => _a('Json'),
                    'permission' => [
                        'resource' => 'json',
                    ],
                    'route'      => 'admin',
                    'module'     => 'shop',
                    'controller' => 'json',
                    'action'     => 'index',
                ],
            ],
        ],
    ],
];