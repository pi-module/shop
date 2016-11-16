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
    'category' => array(
        array(
            'title' => _a('Admin'),
            'name' => 'admin'
        ),
        array(
            'title' => _a('Json output'),
            'name' => 'json'
        ),
        array(
            'title' => _a('View'),
            'name' => 'view'
        ),
        array(
            'title' => _a('Image'),
            'name' => 'image'
        ),
        array(
            'title' => _a('Social'),
            'name' => 'social'
        ),
        array(
            'title' => _a('File'),
            'name' => 'file'
        ),
        array(
            'title' => _a('Vote'),
            'name' => 'vote'
        ),
        array(
            'title' => _a('Favourite'),
            'name' => 'favourite'
        ),
        array(
            'title' => _a('Video'),
            'name' => 'video'
        ),
        array(
            'title' => _a('Sale'),
            'name' => 'sale',
        ),
        array(
            'title' => _a('Order'),
            'name' => 'order'
        ),
        array(
            'title' => _a('Serial'),
            'name' => 'serial'
        ),
    ),
    'item' => array(
        // Admin
        'admin_perpage' => array(
            'category' => 'admin',
            'title' => _a('Perpage'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 25
        ),
        // Json
        'json_perpage' => array(
            'category' => 'json',
            'title' => _a('Perpage on json output'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 100
        ),
        'json_check_password' => array(
            'category' => 'json',
            'title' => _a('Check password for json output'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 0
        ),
        'json_password' => array(
            'category' => 'json',
            'title' => _a('Password for json output'),
            'description' => _a('After use on mobile device , do not change it'),
            'edit' => 'text',
            'filter' => 'string',
            'value' => md5(rand(1,99999)),
        ),
        // View
        'homepage_type' => array(
            'title' => _a('Homepage type'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        'list' => _a('List of all products'),
                        'brand' => _a('List of brands and widgets'),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 'list',
            'category' => 'view',
        ),
        'homepage_widget' => array(
            'category' => 'view',
            'title' => _a('Homepage widget'),
            'description' => _a('Put widget name here, Use `|` as delimiter to separate widgets'),
            'edit' => 'text',
            'filter' => 'string',
        ),
        'homepage_title' => array(
            'category' => 'view',
            'title' => _a('Homepage title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
        ),
        'view_perpage' => array(
            'category' => 'view',
            'title' => _a('Perpage'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 18
        ),
        'view_column' => array(
            'title' => _a('Columns'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        1 => _a('One columns'),
                        2 => _a('Two columns'),
                        3 => _a('Three columns'),
                        4 => _a('Four columns'),
                        6 => _a('Six columns'),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 3,
            'category' => 'view',
        ),
        'view_attribute' => array(
            'category' => 'view',
            'title' => _a('Show attribute fields'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_attach' => array(
            'category' => 'view',
            'title' => _a('Show product attached files'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_price' => array(
            'category' => 'view',
            'title' => _a('Show product price'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_price_title' => array(
            'category' => 'view',
            'title' => _a('Price title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => _a('Volume'),
        ),
        'view_tag' => array(
            'category' => 'view',
            'title' => _a('Show Tags'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_breadcrumbs' => array(
            'category' => 'view',
            'title' => _a('Show breadcrumbs'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_category' => array(
            'category' => 'view',
            'title' => _a('Show category list'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_question' => array(
            'category' => 'view',
            'title' => _a('Show fast question form'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_related' => array(
            'category' => 'view',
            'title' => _a('Show related products'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_incategory' => array(
            'category' => 'view',
            'title' => _a('Show other products from this category'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'product_ribbon' => array(
            'category' => 'view',
            'title' => _a('Product ribbon type'),
            'description' => _a('Use `|` as delimiter to separate terms'),
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => _a('|Professional|Best offer|For you'),
        ),
        // Image
        'image_size' => array(
            'category' => 'image',
            'title' => _a('Image Size'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 1000000
        ),
        'image_quality' => array(
            'category' => 'image',
            'title' => _a('Image quality'),
            'description' => _a('Between 0 to 100 and support both of JPG and PNG, default is 75'),
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 75
        ),
        'image_path' => array(
            'category' => 'image',
            'title' => _a('Image path'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'shop/image'
        ),
        'image_extension' => array(
            'category' => 'image',
            'title' => _a('Image Extension'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'jpg,jpeg,png,gif'
        ),
        'image_largeh' => array(
            'category' => 'image',
            'title' => _a('Large Image height'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 1200
        ),
        'image_largew' => array(
            'category' => 'image',
            'title' => _a('Large Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 1200
        ),
        'image_mediumh' => array(
            'category' => 'image',
            'title' => _a('Medium Image height'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 500
        ),
        'image_mediumw' => array(
            'category' => 'image',
            'title' => _a('Medium Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 500
        ),
        'image_thumbh' => array(
            'category' => 'image',
            'title' => _a('Thumb Image height'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 250
        ),
        'image_thumbw' => array(
            'category' => 'image',
            'title' => _a('Thumb Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 250
        ),
        'image_lightbox' => array(
            'category' => 'image',
            'title' => _a('Use lightbox'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'image_watermark' => array(
            'category' => 'image',
            'title' => _a('Add Watermark'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 0
        ),
        'image_watermark_source' => array(
            'category' => 'image',
            'title' => _a('Watermark Image'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => ''
        ),
        'image_watermark_position' => array(
            'title' => _a('Watermark Positio'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        'top-left' => _a('Top Left'),
                        'top-right' => _a('Top Right'),
                        'bottom-left' => _a('Bottom Left'),
                        'bottom-right' => _a('Bottom Right'),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 'bottom-right',
            'category' => 'image',
        ),
        // Social
        'social_sharing' => array(
            'title' => _t('Social sharing items'),
            'description' => '',
            'edit' => array(
                'type' => 'multi_checkbox',
                'options' => array(
                    'options' => Pi::service('social_sharing')->getList(),
                ),
            ),
            'filter' => 'array',
            'category' => 'social',
        ),
        // File
        'file_size' => array(
            'category' => 'file',
            'title' => _a('File Size'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 1000000
        ),
        'file_path' => array(
            'category' => 'file',
            'title' => _a('File path'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'shop'
        ),
        'file_extension' => array(
            'category' => 'file',
            'title' => _a('File Extension'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => 'jpg,jpeg,png,gif,avi,flv,mp3,mp4,pdf,docs,xdocs,zip,rar'
        ),
        // Vote
        'vote_bar' => array(
            'category' => 'vote',
            'title' => _a('Use vote system'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // favourite
        'favourite_bar' => array(
            'category' => 'favourite',
            'title' => _a('Use favourite system'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // video
        'video_service' => array(
            'category' => 'video',
            'title' => _a('Use video service'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // Sale
        'sale_view' => array(
            'category' => 'sale',
            'title' => _a('Show sale on index page'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'sale_view_number' => array(
            'category' => 'sale',
            'title' => _a('Number of products on sale for index page'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 6
        ),
        'sale_category' => array(
            'title' => _a('Sale category status'),
            'description' => _a('Product status after finish sale on category'),
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        'marketable' => _a('Marketable'),
                        'non-marketable' => _a('Non-marketable'),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 'marketable',
            'category' => 'sale',
        ),
        // Order
        'order_active' => array(
            'category' => 'order',
            'title' => _a('Order is active ?'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_type' => array(
            'category' => 'order',
            'title' => _a('Order type'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        'onetime' => _a('One time'),
                        'recurring' => _a('Recurring'),
                        'installment' => _a('Installment'),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 'onetime',
        ),
        'order_installment_role' => array(
            'category' => 'order',
            'title' => _a('Installment role'),
            'description' => _a('If your order type is installment, when you set role name here, just users of this role can make installment order, if set it empty all users can make installment order'),
            'edit' => 'text',
            'filter' => 'string',
            'value' => ''
        ),
        'order_stock' => array(
            'category' => 'order',
            'title' => _a('Order stock'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        'never' => _a('Never check stock'),
                        'manual' => _a('Manual setting'),
                        'product' => _a('Use product stock'),
                        'property' => _a('Use property stock'),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 'manual',
        ),
        'order_discount' => array(
            'category' => 'order',
            'title' => _a('Active discount system'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // Serial
        'serial_active' => array(
            'category' => 'serial',
            'title' => _a('Active serial number system'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 0
        ),
        'serial_count' => array(
            'category' => 'serial',
            'title' => _a('Count of build serial number'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 5000
        ),
        'serial_role' => array(
            'category' => 'serial',
            'title' => _a('Serial number role'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'P%s-N%s'
        ),
        'serial_role_type' => array(
            'category' => 'serial',
            'title' => _a('Serial number role type'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        1 => _a('Just Number'),
                        2 => _a('Number and Lowercase alphabet'),
                        3 => _a('Number and Uppercase alphabet'),
                        4 => _a('Number, Lowercase and Uppercase alphabet\''),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 2,
        ),
        // Texts
        'text_description_index' => array(
            'category' => 'head_meta',
            'title' => _a('Description for index page'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => ''
        ),
        'force_replace_space' => array(
            'category' => 'head_meta',
            'title' => _a('Force replace space by comma(,)'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
    ),
);