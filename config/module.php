<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * Shop module meta
 *
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */
return array(
    'meta'  => array(
        'title'         => __('Shop'),
        'description'   => __('Shop module for pi.'),
        'version'       => '0.0.1',
        'license'       => 'New BSD',
        'demo'          => 'http://demo.pialog.org'
    ),
    // Author information
    'author'    => array(
        // Author full name, required
        'dev'       => 'Hossein Azizabadi',
        // Email address, optional
        'email'     => 'azizabadi@faragostaresh.com',
        'architect' => 'Hossein Azizabadi',
        'design'    => '@voltan'
    ),

    // Resource
    'resource' => array(
        // Database meta
        'database'  => array(
            // SQL schema/data file
            'sqlfile'   => 'sql/mysql.sql',
        ),
        // Permission specs
        //'permission'    => 'permission.php',
        //'config'        => 'config.php',
        //'user'          => 'user.php',
        //'page'          => 'page.php',
        //'route'         => 'route.php',
        //'navigation'    => 'nav.php',
    ),
);
