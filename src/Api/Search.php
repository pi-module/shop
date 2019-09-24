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

namespace Module\Shop\Api;

use Pi;
use Pi\Search\AbstractSearch;

class Search extends AbstractSearch
{
    /**
     * {@inheritDoc}
     */
    protected $table
        = [
            'product',
            'category',
        ];

    /**
     * {@inheritDoc}
     */
    protected $searchIn
        = [
            'title',
            //'text_summary',
            //'text_description',
        ];

    /**
     * {@inheritDoc}
     */
    protected $meta
        = [
            'id'           => 'id',
            'title'        => 'title',
            'text_summary' => 'content',
            'time_create'  => 'time',
            'slug'         => 'slug',
            'image'        => 'image',
            'path'         => 'path',
        ];

    /**
     * {@inheritDoc}
     */
    protected $condition
        = [
            'status' => 1,
        ];

    /**
     * {@inheritDoc}
     */
    protected $order
        = [
            'time_create DESC',
            'id DESC',
        ];

    /**
     * {@inheritDoc}
     */
    protected function buildUrl(array $item, $table = '')
    {
        switch ($table) {
            case 'category':
                $link = Pi::url(
                    Pi::service('url')->assemble(
                        'shop', [
                        'module'     => $this->getModule(),
                        'controller' => 'category',
                        'slug'       => $item['slug'],
                    ]
                    )
                );
                break;

            case 'product':
                $link = Pi::url(
                    Pi::service('url')->assemble(
                        'shop', [
                        'module'     => $this->getModule(),
                        'controller' => 'product',
                        'slug'       => $item['slug'],
                    ]
                    )
                );
                break;
        }

        return $link;
    }

    /**
     * {@inheritDoc}
     */
    protected function buildImage(array $item, $table = '')
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        $image = '';
        if (isset($item['image']) && !empty($item['image'])) {
            $image = Pi::url(
                sprintf(
                    'upload/%s/thumb/%s/%s',
                    $config['image_path'],
                    $item['path'],
                    $item['image']
                )
            );
        }

        return $image;
    }
}