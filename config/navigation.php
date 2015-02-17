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
    'front'   => array(
        'index' => array(
            'label'         => _a('Index'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'index',
            'action'        => 'index',
        ),


        'search' => array(
            'label'         => _a('Search'),
            'permission'    => array(
                'resource'  => 'search',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'search',
            'action'        => 'index',
        ),

        'user' => array(
            'label'         => _a('My Orders'),
            'permission'    => array(
                'resource'  => 'user',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'user',
            'action'        => 'index',
        ),

        'category' => array(
            'label'         => _a('Category list'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'category',
            'action'        => 'list',
        ),

        'tag' => array(
            'label'         => _a('Tag list'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'tag',
            'action'        => 'list',
        ),
    ),
    'admin' => array(
        'product' => array(
            'label'         => _a('Product'),
            'permission'    => array(
                'resource'  => 'product',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'product',
            'action'        => 'index',
        ),

        'category' => array(
            'label'         => _a('Category'),
            'permission'    => array(
                'resource'  => 'category',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'category',
            'action'        => 'index',
        ),

        'attribute' => array(
            'label'         => _a('Attribute'),
            'permission'    => array(
                'resource'  => 'attribute',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'attribute',
            'action'        => 'index',
        ),

        'attach' => array(
            'label'         => _a('Attach'),
            'permission'    => array(
                'resource'  => 'attach',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'attach',
            'action'        => 'index',
        ),

        'order' => array(
            'label'         => _a('Orders'),
            'permission'    => array(
                'resource'  => 'order',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'order',
            'action'        => 'index',
        ),

        'log' => array(
            'label'         => _a('Logs'),
            'permission'    => array(
                'resource'  => 'log',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'log',
            'action'        => 'index',
        ),

        'tools' => array(
            'label'         => _a('Tools'),
            'permission'    => array(
                'resource'  => 'tools',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'tools',
            'action'        => 'index',
        ),
    ),
);