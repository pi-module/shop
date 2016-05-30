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
    'product-new' => array(
        'name' => 'product-new',
        'title' => _a('New Product'),
        'description' => _a('New Product list'),
        'render' => array('block', 'productNew'),
        'template' => 'product-new',
        'config' => array(
            'category' => array(
                'title' => _a('Category'),
                'description' => '',
                'edit' => 'Module\Shop\Form\Element\Category',
                'filter' => 'string',
                'value' => 0,
            ),
            'number' => array(
                'title' => _a('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'list-type' => array(
                'title' => _a('Product list type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'horizontal' => _a('Horizontal'),
                            'vertical' => _a('Vertical'),
                            'box' => _a('Multi size Box'),
                            'list' => _a('List'),
                            'slide' => _a('Slide'),
                            'slidehover' => _a('Slide by hover effect'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'horizontal',
            ),
            'show-price' => array(
                'title' => _a('Show price'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'blockEffect' => array(
                'title' => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
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
    'product-random' => array(
        'name' => 'product-random',
        'title' => _a('Random Product'),
        'description' => _a('Random Product list'),
        'render' => array('block', 'productRandom'),
        'template' => 'product-random',
        'config' => array(
            'category' => array(
                'title' => _a('Category'),
                'description' => '',
                'edit' => 'Module\Shop\Form\Element\Category',
                'filter' => 'string',
                'value' => 0,
            ),
            'number' => array(
                'title' => _a('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'list-type' => array(
                'title' => _a('Product list type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'horizontal' => _a('Horizontal'),
                            'vertical' => _a('Vertical'),
                            'box' => _a('Multi size Box'),
                            'list' => _a('List'),
                            'slide' => _a('Slide'),
                            'slidehover' => _a('Slide by hover effect'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'horizontal',
            ),
            'show-price' => array(
                'title' => _a('Show price'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'blockEffect' => array(
                'title' => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
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
    'product-tag' => array(
        'name' => 'product-tag',
        'title' => _a('Tag Product'),
        'description' => _a('Products from selected tag'),
        'render' => array('block', 'productTag'),
        'template' => 'product-tag',
        'config' => array(
            'tag-term' => array(
                'title' => _a('Tag term'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'string',
                'value' => '',
            ),
            'number' => array(
                'title' => _a('Number'),
                'description' => '',
                'edit' => 'text',
                'filter' => 'number_int',
                'value' => 10,
            ),
            'list-type' => array(
                'title' => _a('Product list type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'horizontal' => _a('Horizontal'),
                            'vertical' => _a('Vertical'),
                            'box' => _a('Multi size Box'),
                            'list' => _a('List'),
                            'slide' => _a('Slide'),
                            'slidehover' => _a('Slide by hover effect'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'horizontal',
            ),
            'show-price' => array(
                'title' => _a('Show price'),
                'description' => '',
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
            'blockEffect' => array(
                'title' => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
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
    'product-special' => array(
        'name' => 'product-special',
        'title' => _a('Sale Product'),
        'description' => _a('Sale Product list'),
        'render' => array('block', 'productSale'),
        'template' => 'product-sale',
        'config' => array(
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
            'blockEffect' => array(
                'title' => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
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
    'category' => array(
        'name' => 'category',
        'title' => _a('Category'),
        'description' => _a('Category list'),
        'render' => array('block', 'category'),
        'template' => 'category',
        'config' => array(
            'category' => array(
                'title' => _a('Category'),
                'description' => '',
                'edit' => 'Module\Shop\Form\Element\Category',
                'filter' => 'string',
                'value' => 0,
            ),
            'type' => array(
                'title' => _a('Category list type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'horizontal' => _a('Horizontal'),
                            'vertical' => _a('Vertical'),
                            'list' => _a('List'),
                            'slide' => _a('Slide'),
                            'slidehover' => _a('Slide by hover effect'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'horizontal',
            ),
            'blockEffect' => array(
                'title' => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
        ),
    ),
    'basket' => array(
        'name' => 'basket',
        'title' => _a('Basket'),
        'description' => _a('basket'),
        'render' => array('block', 'basket'),
        'template' => 'basket',
        'config' => array(
            'type' => array(
                'title' => _a('Basket type'),
                'description' => '',
                'edit' => array(
                    'type' => 'select',
                    'options' => array(
                        'options' => array(
                            'link' => _a('Link to basket'),
                            'dialog' => _a('Dialog box'),
                        ),
                    ),
                ),
                'filter' => 'text',
                'value' => 'link',
            ),
        ),
    ),
    'search' => array(
        'name' => 'search',
        'title' => _a('Search'),
        'description' => _a('Ajax search block'),
        'render' => array('block', 'search'),
        'template' => 'search',
        'config' => array(
            'blockEffect' => array(
                'title' => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit' => 'checkbox',
                'filter' => 'number_int',
                'value' => 1,
            ),
        ),
    ),
);