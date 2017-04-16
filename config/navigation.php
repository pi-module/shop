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
    'front' => array(
        'category' => array(
            'label' => _a('Category list'),
            'permission' => array(
                'resource' => 'public',
            ),
            'route' => 'shop',
            'module' => 'shop',
            'controller' => 'category',
        ),

        'tag' => array(
            'label' => _a('Tag list'),
            'permission' => array(
                'resource' => 'public',
            ),
            'route' => 'shop',
            'module' => 'shop',
            'controller' => 'tag',
        ),
    ),
    'admin' => array(
        'product' => array(
            'label' => _a('Product'),
            'permission' => array(
                'resource' => 'product',
            ),
            'route' => 'admin',
            'module' => 'shop',
            'controller' => 'product',
            'action' => 'index',
        ),

        'category' => array(
            'label' => _a('Category'),
            'permission' => array(
                'resource' => 'category',
            ),
            'route' => 'admin',
            'module' => 'shop',
            'controller' => 'category',
            'action' => 'index',
            'params'        => array(
                'type'    => 'category',
            ),

            'pages' => array(
                'category' => array(
                    'label' => _a('Category'),
                    'permission' => array(
                        'resource' => 'category',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'category',
                    'action' => 'index',
                    'params'        => array(
                        'type'    => 'category',
                    ),
                ),
                'brand' => array(
                    'label' => _a('Brand'),
                    'permission' => array(
                        'resource' => 'category',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'category',
                    'action' => 'index',
                    'params'        => array(
                        'type'    => 'brand',
                    ),
                ),
                'update-category' => array(
                    'label' => _a('New category'),
                    'permission' => array(
                        'resource' => 'category',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'category',
                    'action' => 'update',
                    'params'        => array(
                        'type'    => 'category',
                    ),
                ),
                'update-brand' => array(
                    'label' => _a('New brand'),
                    'permission' => array(
                        'resource' => 'category',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'category',
                    'action' => 'update',
                    'params'        => array(
                        'type'    => 'brand',
                    ),
                ),
            ),
        ),

        'attribute' => array(
            'label' => _a('Attribute'),
            'permission' => array(
                'resource' => 'attribute',
            ),
            'route' => 'admin',
            'module' => 'shop',
            'controller' => 'attribute',
            'action' => 'index',

            'pages' => array(
                'attribute' => array(
                    'label' => _a('Attribute'),
                    'permission' => array(
                        'resource' => 'attribute',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'attribute',
                    'action' => 'index',
                ),
                'position' => array(
                    'label' => _a('Attribute position'),
                    'permission' => array(
                        'resource' => 'position',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'position',
                    'action' => 'index',
                ),
            ),
        ),

        'property' => array(
            'label' => _a('Order property'),
            'permission' => array(
                'resource' => 'property',
            ),
            'route' => 'admin',
            'module' => 'shop',
            'controller' => 'property',
            'action' => 'index',
        ),

        'attach' => array(
            'label' => _a('Attach'),
            'permission' => array(
                'resource' => 'attach',
            ),
            'route' => 'admin',
            'module' => 'shop',
            'controller' => 'attach',
            'action' => 'index',
        ),

        'discount-system' => array(
            'label' => _a('Discount systems'),
            'permission' => array(
                'resource' => 'discount',
            ),
            'route' => 'admin',
            'module' => 'shop',
            'controller' => 'discount',
            'action' => 'index',
            'pages' => array(
                'discount' => array(
                    'label' => _a('Discount'),
                    'permission' => array(
                        'resource' => 'discount',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'discount',
                    'action' => 'index',
                ),
                'sale' => array(
                    'label' => _a('Sale'),
                    'permission' => array(
                        'resource' => 'sale',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'sale',
                    'action' => 'index',
                ),
                'promotion' => array(
                    'label' => _a('Promotion'),
                    'permission' => array(
                        'resource' => 'promotion',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'promotion',
                    'action' => 'index',
                ),
            ),
        ),

        'tools' => array(
            'label' => _a('Tools'),
            'permission' => array(
                'resource' => 'tools',
            ),
            'route' => 'admin',
            'module' => 'shop',
            'controller' => 'tools',
            'action' => 'index',
            'pages' => array(
                'tools' => array(
                    'label' => _a('Tools'),
                    'permission' => array(
                        'resource' => 'tools',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'tools',
                    'action' => 'index',
                ),
                'sitemap' => array(
                    'label' => _a('Sitemap'),
                    'permission' => array(
                        'resource' => 'tools',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'tools',
                    'action' => 'sitemap',
                ),
                'log' => array(
                    'label' => _a('Logs'),
                    'permission' => array(
                        'resource' => 'log',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'log',
                    'action' => 'index',
                ),
                'json' => array(
                    'label' => _a('Json'),
                    'permission' => array(
                        'resource' => 'json',
                    ),
                    'route' => 'admin',
                    'module' => 'shop',
                    'controller' => 'json',
                    'action' => 'index',
                ),
            ),
        ),
    ),
);