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

namespace Module\Shop\Model;

use Pi\Application\Model\Model;

class Product extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns
        = [
            'id',
            'title',
            'subtitle',
            'slug',
            'code',
            'category',
            'category_main',
            'brand',
            'company_id',
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
            'sold',
            'image',
            'path',
            'main_image',
            'additional_images',
            'comment',
            'point',
            'count',
            'favourite',
            'attach',
            'attribute',
            'related',
            'recommended',
            'stock',
            'stock_alert',
            'stock_type',
            'price',
            'price_discount',
            'price_shipping',
            'price_title',
            'ribbon',
            'setting',
        ];
}
