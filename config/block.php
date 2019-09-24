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
    'product-new'     => [
        'name'        => 'product-new',
        'title'       => _a('New Product'),
        'description' => _a('New Product list'),
        'render'      => ['block', 'productNew'],
        'template'    => 'product-new',
        'config'      => [
            'category'    => [
                'title'       => _a('Category'),
                'description' => '',
                'edit'        => 'Module\Shop\Form\Element\Category',
                'filter'      => 'string',
                'value'       => 0,
            ],
            'number'      => [
                'title'       => _a('Number'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'number_int',
                'value'       => 10,
            ],
            'recommended' => [
                'title'       => _a('Show just recommended'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 0,
            ],
            'list-type'   => [
                'title'       => _a('Product list type'),
                'description' => '',
                'edit'        => [
                    'type'    => 'select',
                    'options' => [
                        'options' => [
                            'horizontal' => _a('Horizontal'),
                            'vertical'   => _a('Vertical'),
                            'box'        => _a('Multi size Box'),
                            'list'       => _a('List'),
                            'slide'      => _a('Slide'),
                            'slidehover' => _a('Slide by hover effect'),
                        ],
                    ],
                ],
                'filter'      => 'text',
                'value'       => 'horizontal',
            ],
            'column'      => [
                'title'       => _a('Columns'),
                'description' => '',
                'edit'        => [
                    'type'    => 'select',
                    'options' => [
                        'options' => [
                            1 => _a('One columns'),
                            2 => _a('Two columns'),
                            3 => _a('Three columns'),
                            4 => _a('Four columns'),
                            6 => _a('Six columns'),
                        ],
                    ],
                ],
                'filter'      => 'text',
                'value'       => 3,
            ],
            'show-ribbon' => [
                'title'       => _a('Show ribbon'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'show-price'  => [
                'title'       => _a('Show price'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'blockEffect' => [
                'title'       => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'more-show'   => [
                'title'       => _a('Show More link to module page'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 0,
            ],
            'more-link'   => [
                'title'       => _a('Set More link to module page'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => '',
            ],
        ],
    ],
    'product-random'  => [
        'name'        => 'product-random',
        'title'       => _a('Random Product'),
        'description' => _a('Random Product list'),
        'render'      => ['block', 'productRandom'],
        'template'    => 'product-random',
        'config'      => [
            'category'    => [
                'title'       => _a('Category'),
                'description' => '',
                'edit'        => 'Module\Shop\Form\Element\Category',
                'filter'      => 'string',
                'value'       => 0,
            ],
            'number'      => [
                'title'       => _a('Number'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'number_int',
                'value'       => 10,
            ],
            'recommended' => [
                'title'       => _a('Show just recommended'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 0,
            ],
            'list-type'   => [
                'title'       => _a('Product list type'),
                'description' => '',
                'edit'        => [
                    'type'    => 'select',
                    'options' => [
                        'options' => [
                            'horizontal' => _a('Horizontal'),
                            'vertical'   => _a('Vertical'),
                            'box'        => _a('Multi size Box'),
                            'list'       => _a('List'),
                            'slide'      => _a('Slide'),
                            'slidehover' => _a('Slide by hover effect'),
                        ],
                    ],
                ],
                'filter'      => 'text',
                'value'       => 'horizontal',
            ],
            'column'      => [
                'title'       => _a('Columns'),
                'description' => '',
                'edit'        => [
                    'type'    => 'select',
                    'options' => [
                        'options' => [
                            1 => _a('One columns'),
                            2 => _a('Two columns'),
                            3 => _a('Three columns'),
                            4 => _a('Four columns'),
                            6 => _a('Six columns'),
                        ],
                    ],
                ],
                'filter'      => 'text',
                'value'       => 3,
            ],
            'show-ribbon' => [
                'title'       => _a('Show ribbon'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'show-price'  => [
                'title'       => _a('Show price'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'blockEffect' => [
                'title'       => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'more-show'   => [
                'title'       => _a('Show More link to module page'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 0,
            ],
            'more-link'   => [
                'title'       => _a('Set More link to module page'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => '',
            ],
        ],
    ],
    'product-tag'     => [
        'name'        => 'product-tag',
        'title'       => _a('Tag Product'),
        'description' => _a('Products from selected tag'),
        'render'      => ['block', 'productTag'],
        'template'    => 'product-tag',
        'config'      => [
            'tag-term'    => [
                'title'       => _a('Tag term'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => '',
            ],
            'number'      => [
                'title'       => _a('Number'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'number_int',
                'value'       => 10,
            ],
            'recommended' => [
                'title'       => _a('Show just recommended'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 0,
            ],
            'list-type'   => [
                'title'       => _a('Product list type'),
                'description' => '',
                'edit'        => [
                    'type'    => 'select',
                    'options' => [
                        'options' => [
                            'horizontal' => _a('Horizontal'),
                            'vertical'   => _a('Vertical'),
                            'box'        => _a('Multi size Box'),
                            'list'       => _a('List'),
                            'slide'      => _a('Slide'),
                            'slidehover' => _a('Slide by hover effect'),
                        ],
                    ],
                ],
                'filter'      => 'text',
                'value'       => 'horizontal',
            ],
            'column'      => [
                'title'       => _a('Columns'),
                'description' => '',
                'edit'        => [
                    'type'    => 'select',
                    'options' => [
                        'options' => [
                            1 => _a('One columns'),
                            2 => _a('Two columns'),
                            3 => _a('Three columns'),
                            4 => _a('Four columns'),
                            6 => _a('Six columns'),
                        ],
                    ],
                ],
                'filter'      => 'text',
                'value'       => 3,
            ],
            'show-ribbon' => [
                'title'       => _a('Show ribbon'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'show-price'  => [
                'title'       => _a('Show price'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'blockEffect' => [
                'title'       => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'more-show'   => [
                'title'       => _a('Show More link to module page'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 0,
            ],
            'more-link'   => [
                'title'       => _a('Set More link to module page'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => '',
            ],
        ],
    ],
    'product-special' => [
        'name'        => 'product-special',
        'title'       => _a('Sale Product'),
        'description' => _a('Sale Product list'),
        'render'      => ['block', 'productSale'],
        'template'    => 'product-sale',
        'config'      => [
            'number'      => [
                'title'       => _a('Number'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'number_int',
                'value'       => 10,
            ],
            'show-ribbon' => [
                'title'       => _a('Show ribbon'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'show-price'  => [
                'title'       => _a('Show price'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'blockEffect' => [
                'title'       => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'more-show'   => [
                'title'       => _a('Show More link to module page'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 0,
            ],
            'more-link'   => [
                'title'       => _a('Set More link to module page'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => '',
            ],
        ],
    ],
    'category-sale'   => [
        'name'        => 'category-sale',
        'title'       => _a('Sale category'),
        'description' => _a('Sale category list'),
        'render'      => ['block', 'categorySale'],
        'template'    => 'category-sale',
        'config'      => [
            'number'      => [
                'title'       => _a('Number'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'number_int',
                'value'       => 10,
            ],
            'blockEffect' => [
                'title'       => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
            'more-show'   => [
                'title'       => _a('Show More link to module page'),
                'description' => '',
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 0,
            ],
            'more-link'   => [
                'title'       => _a('Set More link to module page'),
                'description' => '',
                'edit'        => 'text',
                'filter'      => 'string',
                'value'       => '',
            ],
        ],
    ],
    'category'        => [
        'name'        => 'category',
        'title'       => _a('Category'),
        'description' => _a('Category list'),
        'render'      => ['block', 'category'],
        'template'    => 'category',
        'config'      => [
            'category'    => [
                'title'       => _a('Category'),
                'description' => '',
                'edit'        => 'Module\Shop\Form\Element\Category',
                'filter'      => 'string',
                'value'       => 0,
            ],
            'type'        => [
                'title'       => _a('Category list type'),
                'description' => '',
                'edit'        => [
                    'type'    => 'select',
                    'options' => [
                        'options' => [
                            'horizontal' => _a('Horizontal'),
                            'vertical'   => _a('Vertical'),
                            'list'       => _a('List'),
                            'slide'      => _a('Slide'),
                            'slidehover' => _a('Slide by hover effect'),
                        ],
                    ],
                ],
                'filter'      => 'text',
                'value'       => 'horizontal',
            ],
            'blockEffect' => [
                'title'       => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
        ],
    ],
    'basket'          => [
        'name'        => 'basket',
        'title'       => _a('Basket'),
        'description' => _a('basket'),
        'render'      => ['block', 'basket'],
        'template'    => 'basket',
        'config'      => [
            'type' => [
                'title'       => _a('Basket type'),
                'description' => '',
                'edit'        => [
                    'type'    => 'select',
                    'options' => [
                        'options' => [
                            'link'   => _a('Link to basket'),
                            'dialog' => _a('Dialog box'),
                        ],
                    ],
                ],
                'filter'      => 'text',
                'value'       => 'link',
            ],
        ],
    ],
    'search'          => [
        'name'        => 'search',
        'title'       => _a('Search'),
        'description' => _a('Ajax search block'),
        'render'      => ['block', 'search'],
        'template'    => 'search',
        'config'      => [
            'blockEffect' => [
                'title'       => _a('Use block effects'),
                'description' => _a('Use block effects or set custom effect on theme'),
                'edit'        => 'checkbox',
                'filter'      => 'number_int',
                'value'       => 1,
            ],
        ],
    ],
];