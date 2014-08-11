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
            'title' => _a('Search'),
            'name' => 'search'
        ),
        array(
            'title' => _a('Order'),
            'name' => 'order'
        ),
        array(
            'title' => _a('Texts'),
            'name' => 'text'
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
            'value' => 10
        ),
        // View
        'view_perpage' => array(
            'category' => 'view',
            'title' => _a('Perpage'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 20
        ),
        'view_related' => array(
            'category' => 'view',
            'title' => _a('Show related products'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_extra' => array(
            'category' => 'view',
            'title' => _a('Show extra fields'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_attach' => array(
            'category' => 'view',
            'title' => _a('Show product attacehed files'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_incategory' => array(
            'category' => 'view',
            'title' => _a('Show product attacehed files'),
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
        'view_review_official' => array(
            'category' => 'view',
            'title' => _a('Show official review'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_review_user' => array(
            'category' => 'view',
            'title' => _a('Show user review'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_review_submit' => array(
            'category' => 'view',
            'title' => _a('Show submit review'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_special' => array(
            'category' => 'view',
            'title' => _a('Show special'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_special_number' => array(
            'category' => 'view',
            'title' => _a('Number of products on special'),
            'description' => '',
            'edit' => 'text',
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
        'view_stock' => array(
            'category' => 'view',
            'title' => _a('Show stock count'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'view_tag' => array(
            'category' => 'view',
            'title' => _a('Show Tags'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
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
            'value' => 800
        ),
        'image_largew' => array(
            'category' => 'image',
            'title' => _a('Large Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 800
        ),
        'image_mediumh' => array(
            'category' => 'image',
            'title' => _a('Medium Image height'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 300
        ),
        'image_mediumw' => array(
            'category' => 'image',
            'title' => _a('Medium Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 300
        ),
        'image_thumbh' => array(
            'category' => 'image',
            'title' => _a('Thumb Image height'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 150
        ),
        'image_thumbw' => array(
            'category' => 'image',
            'title' => _a('Thumb Image width'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'number_int',
            'value' => 150
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
        'social_gplus' => array(
            'category' => 'social',
            'title' => _a('Show Google Plus'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'social_facebook' => array(
            'category' => 'social',
            'title' => _a('Show facebook'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'social_twitter' => array(
            'category' => 'social',
            'title' => _a('Show Twitter'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
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
        // Search 
        'search_type' => array(
            'category' => 'search',
            'title' => _a('Show search type'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_price' => array(
            'category' => 'search',
            'title' => _a('Show search price'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'search_category' => array(
            'category' => 'search',
            'title' => _a('Show search category'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // Order
        'order_method' => array(
            'title' => _a('Order method'),
            'description' => '',
            'edit' => array(
                'type' => 'select',
                'options' => array(
                    'options' => array(
                        'both' => _a('Both'),
                        'online' => _a('Online'),
                        'offline' => _a('Offline'),
                        'inactive' => _a('Inactive'),
                    ),
                ),
            ),
            'filter' => 'text',
            'value' => 'both',
            'category' => 'order',
        ),
        'order_code_prefix' => array(
            'category' => 'order',
            'title' => _a('Code Prefix'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'pi' 
        ),
        'order_mail' => array(
            'category' => 'order',
            'title' => _a('List of mails for send order notification'),
            'description' => _a('Use `|` as delimiter to separate mails'),
            'edit' => 'textarea',
            'filter' => 'string',
            'value' => ''
        ),
        'order_name' => array(
            'category' => 'order',
            'title' => _a('Show name'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_email' => array(
            'category' => 'order',
            'title' => _a('Show email'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_phone' => array(
            'category' => 'order',
            'title' => _a('Show phone'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_mobile' => array(
            'category' => 'order',
            'title' => _a('Show mobile'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_company' => array(
            'category' => 'order',
            'title' => _a('Show company'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_address' => array(
            'category' => 'order',
            'title' => _a('Show address'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_country' => array(
            'category' => 'order',
            'title' => _a('Show country'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_city' => array(
            'category' => 'order',
            'title' => _a('Show city'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_zip' => array(
            'category' => 'order',
            'title' => _a('Show Zip code'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_packing' => array(
            'category' => 'order',
            'title' => _a('Show packing'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_location' => array(
            'category' => 'order',
            'title' => _a('Show location'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_delivery' => array(
            'category' => 'order',
            'title' => _a('Show delivery'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        'order_payment' => array(
            'category' => 'order',
            'title' => _a('Show payment'),
            'description' => '',
            'edit' => 'checkbox',
            'filter' => 'number_int',
            'value' => 1
        ),
        // Texts
        'text_title_homepage' => array(
            'category' => 'text',
            'title' => _a('Module main title'),
            'description' => _a('Title for main page and all non-title pages'),
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'Newest products from this website'
        ),
        'text_description_homepage' => array(
            'category' => 'text',
            'title' => _a('Module main description'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'Newest products from this website'
        ),
        'text_keywords_homepage' => array(
            'category' => 'text',
            'title' => _a('Module main keywords'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'product,shop,online,website'
        ),
        'text_title_search' => array(
            'category' => 'text',
            'title' => _a('Module search page title'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'Newest products from this website'
        ),
        'text_description_search' => array(
            'category' => 'text',
            'title' => _a('Module search page description'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'Newest products from this website'
        ),
        'text_keywords_search' => array(
            'category' => 'text',
            'title' => _a('Module search page keywords'),
            'description' => '',
            'edit' => 'text',
            'filter' => 'string',
            'value' => 'product,shop,online,website'
        ),
    ),
);