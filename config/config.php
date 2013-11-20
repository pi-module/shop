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
            'title' => __('Property'),
            'name' => 'property'
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
        'view_property' => array(
            'category' => 'view',
            'title' => __('Show product property'),
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
            'value' => 1
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
        'search_property_1' => array(
            'category' => 'search',
            'title' => __('Show search property 1'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_2' => array(
            'category' => 'search',
            'title' => __('Show search property 2'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_3' => array(
            'category' => 'search',
            'title' => __('Show search property 3'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_4' => array(
            'category' => 'search',
            'title' => __('Show search property 4'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_5' => array(
            'category' => 'search',
            'title' => __('Show search property 5'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_6' => array(
            'category' => 'search',
            'title' => __('Show search property 6'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_7' => array(
            'category' => 'search',
            'title' => __('Show search property 7'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_8' => array(
            'category' => 'search',
            'title' => __('Show search property 8'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_9' => array(
            'category' => 'search',
            'title' => __('Show search property 9'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_property_10' => array(
            'category' => 'search',
            'title' => __('Show search property 10'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // Hidden property
        'property_1_title' => array(
            'category' => 'property',
            'title' => __('Property 1 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 1'),
            'visible' => 0,
        ),
        'property_1_option' => array(
            'category' => 'property',
            'title' => __('Property 1 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_2_title' => array(
            'category' => 'property',
            'title' => __('Property 2 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 2'),
            'visible' => 0,
        ),
        'property_2_option' => array(
            'category' => 'property',
            'title' => __('Property 2 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_3_title' => array(
            'category' => 'property',
            'title' => __('Property 3 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 3'),
            'visible' => 0,
        ),
        'property_3_option' => array(
            'category' => 'property',
            'title' => __('Property 3 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_4_title' => array(
            'category' => 'property',
            'title' => __('Property 4 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 4'),
            'visible' => 0,
        ),
        'property_4_option' => array(
            'category' => 'property',
            'title' => __('Property 4 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_5_title' => array(
            'category' => 'property',
            'title' => __('Property 5 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 5'),
            'visible' => 0,
        ),
        'property_5_option' => array(
            'category' => 'property',
            'title' => __('Property 5 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_6_title' => array(
            'category' => 'property',
            'title' => __('Property 6 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 6'),
            'visible' => 0,
        ),
        'property_6_option' => array(
            'category' => 'property',
            'title' => __('Property 6 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_7_title' => array(
            'category' => 'property',
            'title' => __('Property 7 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 7'),
            'visible' => 0,
        ),
        'property_7_option' => array(
            'category' => 'property',
            'title' => __('Property 7 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_8_title' => array(
            'category' => 'property',
            'title' => __('Property 8 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 8'),
            'visible' => 0,
        ),
        'property_8_option' => array(
            'category' => 'property',
            'title' => __('Property 8 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_9_title' => array(
            'category' => 'property',
            'title' => __('Property 9 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 9'),
            'visible' => 0,
        ),
        'property_9_option' => array(
            'category' => 'property',
            'title' => __('Property 9 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),
        'property_10_title' => array(
            'category' => 'property',
            'title' => __('Property 10 title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => __('Property 10'),
            'visible' => 0,
        ),
        'property_10_option' => array(
            'category' => 'property',
            'title' => __('Property 10 options'),
            'description' => '',
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => '',
            'visible' => 0,
        ),

    ),
);