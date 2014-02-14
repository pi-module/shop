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
            'label'         => _t('Index'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'index',
            'action'        => 'index',
        ),


        'search' => array(
            'label'         => _t('Search'),
            'permission'    => array(
                'resource'  => 'search',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'search',
            'action'        => 'index',
        ),

        'user' => array(
            'label'         => _t('My Order'),
            'permission'    => array(
                'resource'  => 'user',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'user',
            'action'        => 'index',
        ),

        'category' => array(
            'label'         => _t('Category list'),
            'permission'    => array(
                'resource'  => 'public',
            ),
            'route'         => 'shop',
            'module'        => 'shop',
            'controller'    => 'category',
            'action'        => 'list',
        ),

        'tag' => array(
            'label'         => _t('Tag list'),
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
            'label'         => _t('Product'),
            'permission'    => array(
                'resource'  => 'product',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'product',
            'action'        => 'index',
        ),

        'category' => array(
            'label'         => _t('Category'),
            'permission'    => array(
                'resource'  => 'category',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'category',
            'action'        => 'index',
        ),

        'attach' => array(
            'label'         => _t('Attach'),
            'permission'    => array(
                'resource'  => 'attach',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'attach',
            'action'        => 'index',
        ),

        'order' => array(
            'label'         => _t('Order'),
            'permission'    => array(
                'resource'  => 'order',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'order',
            'action'        => 'index',
        ),

        'checkout' => array(
            'label'         => _t('Checkout'),
            'permission'    => array(
                'resource'  => 'checkout',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'checkout',
            'action'        => 'index',
        ),

        'log' => array(
            'label'         => _t('Logs'),
            'permission'    => array(
                'resource'  => 'log',
            ),
            'route'         => 'admin',
            'module'        => 'shop',
            'controller'    => 'log',
            'action'        => 'index',
        ),
    ),
);