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
    'meta'  => array(
        'title'         => __('Shop'),
        'description'   => __('Shop module for pi.'),
        'version'       => '0.0.9',
        'license'       => 'New BSD',
        'logo'          => 'image/logo.png',
        'readme'        => 'docs/readme.txt',
        'demo'          => 'http://pialog',
        'icon'          => 'fa fa-shopping-cart',
    ),
    // Author information
    'author'    => array(
        'dev'       => 'Hossein Azizabadi',
        'email'     => 'azizabadi@faragostaresh.com',
        'architect' => '@voltan',
        'design'    => '@voltan'
    ),
    // Resource
    'resource' => array(
        'database'      => 'database.php',
        'config'        => 'config.php',
        'permission'    => 'permission.php',
        'page'          => 'page.php',
        'navigation'    => 'navigation.php',
        'block'         => 'block.php',
        'route'         => 'route.php',
        'comment'       => 'comment.php',
    ),
);
