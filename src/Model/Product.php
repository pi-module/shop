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
namespace Module\Shop\Model;

use Pi\Application\Model\Model;

class Product extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns = array(
        'id',
        'title',
        'subtitle',
        'slug',
        'category',
        'text_summary',
        'text_description',
        'seo_title',
        'seo_keywords',
        'seo_description',
        'status',
        'time_create',
        'time_update',
        'uid',
        'hits',
        'sales',
        'image',
        'path',
        'comment',
        'point',
        'count',
        'attach',
        'attribute',
        'related',
        'recommended',
        'category_main',
        'stock',
        'stock_alert',
        'stock_type',
        'price',
        'price_discount',
        'price_shipping',
        'price_title',
        'setting'
    );
}