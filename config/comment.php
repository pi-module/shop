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
    'shop' => [
        'title'    => _a('Shop comments'),
        'icon'     => 'icon-post',
        'callback' => 'Module\Shop\Api\Comment',
        'locator'  => 'Module\Shop\Api\Comment',
    ],
];
