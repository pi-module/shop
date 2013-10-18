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
        'version'       => '0.0.2',
        'license'       => 'New BSD',
        'demo'          => 'http://demo.pialog.org'
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
        'permission'    => 'permission.php',
        'page'          => 'page.php',
        'navigation'    => 'navigation.php',
    ),
);
