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
    ),
);