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
    'product-new'    => array(
        'name'          => 'product-new',
        'title'         => _a('New Product'),
        'description'   => _a('New Product list'),
        'render'        => array('block', 'productNew'),
        'template'      => 'product-new',
        'config'        => array(
            'number' => array(
                'title' => _a('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'show-price' => array(
                'title' => _a('Show price'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'more-show' => array(
                'title' => _a('Show More link to module page'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 0,
            ),
            'more-link' => array(
                'title' => _a('Set More link to module page'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
        ),
    ),
    'product-random'    => array(
        'name'          => 'product-random',
        'title'         => _a('Random Product'),
        'description'   => _a('RandomProduct list'),
        'render'        => array('block', 'productRandom'),
        'template'      => 'product-random',
        'config'        => array(
            'number' => array(
                'title' => _a('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'show-price' => array(
                'title' => _a('Show price'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'more-show' => array(
                'title' => _a('Show More link to module page'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 0,
            ),
            'more-link' => array(
                'title' => _a('Set More link to module page'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
        ),
    ),
    'category'    => array(
        'name'          => 'category',
        'title'         => _a('Category'),
        'description'   => _a('Category list'),
        'render'        => array('block', 'category'),
        'template'      => 'category',
        'config'        => array(
            'type' => array(
                'title' => _a('Category show type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'simple' => _a('Simple list'),
                            'advanced' => _a('Advanced list'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'simple',
            ),
        ),
    ),
);