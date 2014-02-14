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
        'public'    => array(
            'title'         => _t('Global public resource'),
            'access'        => array(
                'guest',
                'member',
            ),
        ),
        'search' => array(
            'title'         => _t('Search'),
            'access'        => array(
                'guest',
                'member',
            ),
        ),
        'user' => array(
            'title'         => _t('User'),
            'access'        => array(
                'member',
            ),
        ),
        'review' => array(
            'title'         => _t('Review'),
            'access'        => array(
                'member',
            ),
        ),
        'checkout' => array(
            'title'         => _t('Checkout'),
            'access'        => array(
                'member',
            ),
        ),
    ),
    // Admin section
    'admin' => array(
        'product'        => array(
            'title'         => __('Product'),
            'access'        => array(
                //'admin',
            ),
        ),
        'category'      => array(
            'title'         => __('Category'),
            'access'        => array(
                //'admin',
            ),
        ),
        'attach'         => array(
            'title'         => __('Attach'),
            'access'        => array(
                //'admin',
            ),
        ),
        'order'  => array(
            'title'         => __('Order'),
            'access'        => array(
                //'admin',
            ),
        ),
        'checkout'  => array(
            'title'         => __('Checkout'),
            'access'        => array(
                //'admin',
            ),
        ),
        'log'  => array(
            'title'         => __('Logs'),
            'access'        => array(
                //'admin',
            ),
        ),
    ),
);