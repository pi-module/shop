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
        'title'         => _b('New Product'),
        'description'   => _b('New Product list'),
        'render'        => array('block', 'productNew'),
        'template'      => 'product-new',
        'config'        => array(
            'number' => array(
                'title' => _b('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'show-price' => array(
                'title' => _b('Show price'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'more-show' => array(
                'title' => _b('Show More link to module page'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 0,
            ),
            'more-link' => array(
                'title' => _b('Set More link to module page'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
        ),
    ),
    'product-random'    => array(
        'name'          => 'product-random',
        'title'         => _b('Random Product'),
        'description'   => _b('RandomProduct list'),
        'render'        => array('block', 'productRandom'),
        'template'      => 'product-random',
        'config'        => array(
            'number' => array(
                'title' => _b('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'show-price' => array(
                'title' => _b('Show price'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'more-show' => array(
                'title' => _b('Show More link to module page'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 0,
            ),
            'more-link' => array(
                'title' => _b('Set More link to module page'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
        ),
    ),
    'category'    => array(
        'name'          => 'category',
        'title'         => _b('Category'),
        'description'   => _b('Category list'),
        'render'        => array('block', 'category'),
        'template'      => 'category',
        'config'        => array(
            'type' => array(
                'title' => _b('Category show type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'simple' => _b('Simple list'),
                            'advanced' => _b('Advanced list'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'simple',
            ),
        ),
    ),
);