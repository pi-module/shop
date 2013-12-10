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
            'title' => __('Admin'),
            'name' => 'admin'
        ),
        array(
            'title' => __('View'),
            'name' => 'view'
        ),
        array(
            'title' => __('Image'),
            'name' => 'image'
        ),
        array(
            'title' => __('Social'),
            'name' => 'social'
        ),
        array(
            'title' => __('File'),
            'name' => 'file'
        ),
        array(
            'title' => __('Search'),
            'name' => 'search'
        ),
        array(
            'title' => __('Order'),
            'name' => 'order'
        ),
    ),
    'item' => array(
    	// Admin
        'admin_perpage' => array(
            'category' => 'admin',
            'title' => __('Perpage'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 10
        ),
        // View
        'view_perpage' => array(
            'category' => 'view',
            'title' => __('Perpage'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 20
        ),
        'view_related' => array(
            'category' => 'view',
            'title' => __('Show related products'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_extra' => array(
            'category' => 'view',
            'title' => __('Show extra fields'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_attach' => array(
            'category' => 'view',
            'title' => __('Show product attacehed files'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_incategory' => array(
            'category' => 'view',
            'title' => __('Show product attacehed files'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_price' => array(
            'category' => 'view',
            'title' => __('Show product price'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_review_official' => array(
            'category' => 'view',
            'title' => __('Show official review'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_review_user' => array(
            'category' => 'view',
            'title' => __('Show user review'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_review_submit' => array(
            'category' => 'view',
            'title' => __('Show submit review'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_special' => array(
            'category' => 'view',
            'title' => __('Show special'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_special_number' => array(
            'category' => 'view',
            'title' => __('Number of products on special'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 1
        ),
        // Image
        'image_size' => array(
            'category' => 'image',
            'title' => __('Image Size'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 1000000
        ),
        'image_path' => array(
            'category' => 'image',
            'title' => __('Image path'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'shop/image'
        ),
        'image_extension' => array(
            'category' => 'image',
            'title' => __('Image Extension'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'jpg,jpeg,png,gif'
        ),
        'image_largeh' => array(
            'category' => 'image',
            'title' => __('Large Image height'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 800
        ),
        'image_largew' => array(
            'category' => 'image',
            'title' => __('Large Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 800
        ),
        'image_mediumh' => array(
            'category' => 'image',
            'title' => __('Medium Image height'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 300
        ),
        'image_mediumw' => array(
            'category' => 'image',
            'title' => __('Medium Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 300
        ),
        'image_thumbh' => array(
            'category' => 'image',
            'title' => __('Thumb Image height'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 150
        ),
        'image_thumbw' => array(
            'category' => 'image',
            'title' => __('Thumb Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 150
        ),
        'image_lightbox' => array(
            'category' => 'image',
            'title' => __('Use lightbox'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'image_watermark' => array(
            'category' => 'image',
            'title' => __('Add Watermark'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 0
        ),
        'image_watermark_source' => array(
            'category' => 'image',
            'title' => __('Watermark Image'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => ''
        ),
        'image_watermark_position' => array(
            'title' => __('Watermark Positio'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        'top-left' => __('Top Left'),
                        'top-right' => __('Top Right'),
                        'bottom-left' => __('Bottom Left'),
                        'bottom-right' => __('Bottom Right'),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 'bottom-right',
            'category' => 'image',
        ),
        // Social
        'social_gplus' => array(
            'category' => 'social',
            'title' => __('Show Google Plus'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'social_facebook' => array(
            'category' => 'social',
            'title' => __('Show facebook'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'social_twitter' => array(
            'category' => 'social',
            'title' => __('Show Twitter'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // File
        'file_size' => array(
            'category' => 'file',
            'title' => __('File Size'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 1000000
        ),
        'file_path' => array(
            'category' => 'file',
            'title' => __('File path'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'shop'
        ),
        'file_extension' => array(
            'category' => 'file',
            'title' => __('File Extension'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => 'jpg,jpeg,png,gif,avi,flv,mp3,mp4,pdf,docs,xdocs,zip,rar'
        ),
        // Search 
        'search_type' => array(
            'category' => 'search',
            'title' => __('Show search type'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_price' => array(
            'category' => 'search',
            'title' => __('Show search price'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_category' => array(
            'category' => 'search',
            'title' => __('Show search category'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // Order
        'order_code_prefix' => array(
            'category' => 'order',
            'title' => __('Code Prefix'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'pi' 
        ),
        'order_name' => array(
            'category' => 'order',
            'title' => __('Show name'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_email' => array(
            'category' => 'order',
            'title' => __('Show email'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_phone' => array(
            'category' => 'order',
            'title' => __('Show phone'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_mobile' => array(
            'category' => 'order',
            'title' => __('Show mobile'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_company' => array(
            'category' => 'order',
            'title' => __('Show company'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_address' => array(
            'category' => 'order',
            'title' => __('Show address'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_country' => array(
            'category' => 'order',
            'title' => __('Show country'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_city' => array(
            'category' => 'order',
            'title' => __('Show city'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_zip' => array(
            'category' => 'order',
            'title' => __('Show Zip code'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_packing' => array(
            'category' => 'order',
            'title' => __('Show packing'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_location' => array(
            'category' => 'order',
            'title' => __('Show location'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_delivery' => array(
            'category' => 'order',
            'title' => __('Show delivery'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_payment' => array(
            'category' => 'order',
            'title' => __('Show payment'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
    ),
);