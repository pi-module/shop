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
    'category' => [
        [
            'title' => _a('Admin'),
            'name'  => 'admin',
        ],
        [
            'title' => _a('User'),
            'name'  => 'user',
        ],
        [
            'title' => _a('View'),
            'name'  => 'view',
        ],
        [
            'title' => _a('Image'),
            'name'  => 'image',
        ],
        [
            'title' => _a('Social'),
            'name'  => 'social',
        ],
        [
            'title' => _a('File'),
            'name'  => 'file',
        ],
        [
            'title' => _a('Vote'),
            'name'  => 'vote',
        ],
        [
            'title' => _a('Favourite'),
            'name'  => 'favourite',
        ],
        [
            'title' => _a('Video'),
            'name'  => 'video',
        ],
        [
            'title' => _a('Sale'),
            'name'  => 'sale',
        ],
        [
            'title' => _a('Order'),
            'name'  => 'order',
        ],
        [
            'title' => _a('Serial'),
            'name'  => 'serial',
        ],
        [
            'title' => _a('Company dashboard'),
            'name'  => 'dashboard',
        ],
    ],
    'item'     => [
        // Admin
        'admin_perpage'             => [
            'category'    => 'admin',
            'title'       => _a('Perpage'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 25,
        ],
        'brand_system'              => [
            'category'    => 'admin',
            'title'       => _a('Active brand system'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        // User
        'processing_user'           => [
            'category'    => 'user',
            'title'       => _a('Processing User'),
            'description' => _a(
                'Active and check user table on shop module and do some actions like update count of products or total payment or disable/enable order product by user'
            ),
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
        'processing_order_limit'    => [
            'category'    => 'user',
            'title'       => _a('Each user can order each product one time'),
            'description' => _a('Processing User should be active !'),
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
        'processing_disable_order'  => [
            'category'    => 'user',
            'title'       => _a('Next order disable for each user until finish payment'),
            'description' => _a('Processing User should be active !'),
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
        'processing_login'          => [
            'category'    => 'user',
            'title'       => _a('User should be login before add products to cart'),
            'description' => _a('Processing User should be active !'),
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        // View
        'homepage_type'             => [
            'title'       => _a('Homepage type'),
            'description' => '',
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        'list'     => _a('List of all products'),
                        'widget'   => _a('List of selected widgets'),
                        'brand'    => _a('List of brands'),
                        'category' => _a('List of categories'),
                    ],
                ],
            ],
            'filter'      => 'text',
            'value'       => 'list',
            'category'    => 'view',
        ],
        'homepage_widget'           => [
            'category'    => 'view',
            'title'       => _a('Homepage widget'),
            'description' => _a('Put widget name here, Use `|` as delimiter to separate widgets'),
            'edit'        => 'text',
            'filter'      => 'string',
        ],
        'homepage_title'            => [
            'category'    => 'view',
            'title'       => _a('Homepage title'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
        ],
        'view_perpage'              => [
            'category'    => 'view',
            'title'       => _a('Perpage'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 18,
        ],
        'view_column'               => [
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
            'category'    => 'view',
        ],
        'product_template'          => [
            'title'       => _a('Product page template'),
            'description' => '',
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        'plain' => _a('Plain'),
                        'tab'   => _a('Tab'),
                    ],
                ],
            ],
            'filter'      => 'text',
            'value'       => 'plain',
            'category'    => 'view',
        ],
        'view_price_filter'         => [
            'category'    => 'view',
            'title'       => _a('Show price filter'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_attribute'            => [
            'category'    => 'view',
            'title'       => _a('Show attribute fields'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_attach'               => [
            'category'    => 'view',
            'title'       => _a('Show product attached files'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_price'                => [
            'category'    => 'view',
            'title'       => _a('Show product price'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_add_to_cart'          => [
            'category'    => 'view',
            'title'       => _a('Show add to cart on list'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
        'view_price_title'          => [
            'category'    => 'view',
            'title'       => _a('Price title'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => _a('Volume'),
        ],
        'view_tag'                  => [
            'category'    => 'view',
            'title'       => _a('Show Tags'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_breadcrumbs'          => [
            'category'    => 'view',
            'title'       => _a('Show breadcrumbs'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_category'             => [
            'category'    => 'view',
            'title'       => _a('Show category list'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_question'             => [
            'category'    => 'view',
            'title'       => _a('Show fast question form'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_related'              => [
            'category'    => 'view',
            'title'       => _a('Show related products'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_incategory'           => [
            'category'    => 'view',
            'title'       => _a('Show other products from this category'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_incategory_count'     => [
            'category'    => 'view',
            'title'       => _a('Number of other products from this category'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 20,
        ],
        'product_ribbon'            => [
            'category'    => 'view',
            'title'       => _a('Product ribbon type'),
            'description' => _a('Use `|` as delimiter to separate terms'),
            'edit'        => 'textarea',
            'filter'      => 'string',
            'value'       => _a('|Professional|Best offer|For you'),
        ],
        'view_description_category' => [
            'category'    => 'view',
            'title'       => _a('Show category description on category page'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'view_description_product'  => [
            'category'    => 'view',
            'title'       => _a('Show category description on product page'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
        'new_product'               => [
            'category'    => 'view',
            'title'       => _a('New product days'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 7,
        ],

        // Image
        'image_minw'                => [
            'category'    => 'image',
            'title'       => _t('Min Image width (upload)'),
            'description' => _t('This config can override media module value'),
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],
        'image_minh'                => [
            'category'    => 'image',
            'title'       => _t('Min Image height (upload)'),
            'description' => _t('This config can override media module value'),
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],
        'image_largeh'              => [
            'category'    => 'image',
            'title'       => _a('Large Image height'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 1200,
        ],
        'image_largew'              => [
            'category'    => 'image',
            'title'       => _a('Large Image width'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 1200,
        ],
        'image_itemh'               => [
            'category'    => 'image',
            'title'       => _a('Item Image height'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 800,
        ],
        'image_itemw'               => [
            'category'    => 'image',
            'title'       => _a('Item Image width'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 800,
        ],
        'image_mediumh'             => [
            'category'    => 'image',
            'title'       => _a('Medium Image height'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 500,
        ],
        'image_mediumw'             => [
            'category'    => 'image',
            'title'       => _a('Medium Image width'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 500,
        ],
        'image_thumbh'              => [
            'category'    => 'image',
            'title'       => _a('Thumb Image height'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 250,
        ],
        'image_thumbw'              => [
            'category'    => 'image',
            'title'       => _a('Thumb Image width'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 250,
        ],
        'image_quality'             => [
            'category'    => 'image',
            'title'       => _a('Image quality'),
            'description' => _a('Between 0 to 100 and support both of JPG and PNG, default is 75. This config can override media module value'),
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => 75,
        ],
        'image_size'                => [
            'category'    => 'image',
            'title'       => _a('Image Size'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 1000000,
        ],
        'image_path'                => [
            'category'    => 'image',
            'title'       => _a('Image path'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => 'shop/image',
        ],
        'image_extension'           => [
            'category'    => 'image',
            'title'       => _a('Image Extension'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => 'jpg,jpeg,png,gif',
        ],
        'image_lightbox'            => [
            'category'    => 'image',
            'title'       => _a('Use lightbox'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'image_watermark'           => [
            'category'    => 'image',
            'title'       => _a('Add Watermark'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
        'image_watermark_source'    => [
            'category'    => 'image',
            'title'       => _a('Watermark Image'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => '',
        ],
        'image_watermark_position'  => [
            'title'       => _a('Watermark Positio'),
            'description' => '',
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        'top-left'     => _a('Top Left'),
                        'top-right'    => _a('Top Right'),
                        'bottom-left'  => _a('Bottom Left'),
                        'bottom-right' => _a('Bottom Right'),
                    ],
                ],
            ],
            'filter'      => 'text',
            'value'       => 'bottom-right',
            'category'    => 'image',
        ],

        // Social
        'social_sharing'            => [
            'title'       => _t('Social sharing items'),
            'description' => '',
            'edit'        => [
                'type'    => 'multi_checkbox',
                'options' => [
                    'options' => Pi::service('social_sharing')->getList(),
                ],
            ],
            'filter'      => 'array',
            'category'    => 'social',
        ],

        // File
        'file_size'                 => [
            'category'    => 'file',
            'title'       => _a('File Size'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 1000000,
        ],
        'file_path'                 => [
            'category'    => 'file',
            'title'       => _a('File path'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => 'shop',
        ],
        'file_extension'            => [
            'category'    => 'file',
            'title'       => _a('File Extension'),
            'description' => '',
            'edit'        => 'textarea',
            'filter'      => 'string',
            'value'       => 'jpg,jpeg,png,gif,avi,flv,mp3,mp4,pdf,docs,xdocs,zip,rar',
        ],

        // Vote
        'vote_bar'                  => [
            'category'    => 'vote',
            'title'       => _a('Use vote system'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],

        // favourite
        'favourite_bar'             => [
            'category'    => 'favourite',
            'title'       => _a('Use favourite system'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],

        // video
        'video_service'             => [
            'category'    => 'video',
            'title'       => _a('Use video service'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],

        // Sale
        'sale_view'                 => [
            'category'    => 'sale',
            'title'       => _a('Show sale on index page'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'sale_view_number'          => [
            'category'    => 'sale',
            'title'       => _a('Number of products on sale for index page'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 6,
        ],
        'sale_category'             => [
            'title'       => _a('Sale category status'),
            'description' => _a('Product status after finish sale on category'),
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        'marketable'     => _a('Marketable'),
                        'non-marketable' => _a('Non-marketable'),
                    ],
                ],
            ],
            'filter'      => 'text',
            'value'       => 'marketable',
            'category'    => 'sale',
        ],

        // Order
        'order_active'              => [
            'category'    => 'order',
            'title'       => _a('Order is active ?'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'order_stock'               => [
            'category'    => 'order',
            'title'       => _a('Order stock'),
            'description' => '',
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        'never'    => _a('Never check stock'),
                        'manual'   => _a('Manual setting'),
                        'product'  => _a('Use product stock'),
                        'property' => _a('Use property stock'),
                    ],
                ],
            ],
            'filter'      => 'text',
            'value'       => 'manual',
        ],
        'order_discount'            => [
            'category'    => 'order',
            'title'       => _a('Active discount system'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
        'order_cart_simple'         => [
            'category'    => 'order',
            'title'       => _a('Make cart simple'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],

        // Serial
        'serial_active'             => [
            'category'    => 'serial',
            'title'       => _a('Active serial number system'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 0,
        ],
        'serial_count'              => [
            'category'    => 'serial',
            'title'       => _a('Count of build serial number'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'number_int',
            'value'       => 5000,
        ],
        'serial_role'               => [
            'category'    => 'serial',
            'title'       => _a('Serial number role'),
            'description' => '',
            'edit'        => 'text',
            'filter'      => 'string',
            'value'       => 'P%s-N%s',
        ],
        'serial_role_type'          => [
            'category'    => 'serial',
            'title'       => _a('Serial number role type'),
            'description' => '',
            'edit'        => [
                'type'    => 'select',
                'options' => [
                    'options' => [
                        1 => _a('Just Number'),
                        2 => _a('Number and Lowercase alphabet'),
                        3 => _a('Number and Uppercase alphabet'),
                        4 => _a('Number, Lowercase and Uppercase alphabet\''),
                    ],
                ],
            ],
            'filter'      => 'text',
            'value'       => 2,
        ],

        // dashboard
        'dashboard_active'               => [
            'category'    => 'dashboard',
            'title'       => _a('Active dashboard for companies and users'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],

        // Texts
        'text_description_index'    => [
            'category'    => 'head_meta',
            'title'       => _a('Description for index page'),
            'description' => '',
            'edit'        => 'textarea',
            'filter'      => 'string',
            'value'       => '',
        ],
        'force_replace_space'       => [
            'category'    => 'head_meta',
            'title'       => _a('Force replace space by comma(,)'),
            'description' => '',
            'edit'        => 'checkbox',
            'filter'      => 'number_int',
            'value'       => 1,
        ],
    ],
];
