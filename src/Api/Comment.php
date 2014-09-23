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
namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractComment;

class Comment extends AbstractComment
{
    /** @var string */
    protected $module = 'shop';

    /**
     * Get target data
     *
     * @param int|int[] $item Item id(s)
     *
     * @return array
     */
    public function get($item)
    {
        
        $result = array();
        $items = (array) $item;

        // Set options
        $products = Pi::api('product', 'shop')->getListFromId($items);

        foreach ($items as $id) {
            $result[$id] = array(
                'id'    => $products[$id]['id'],
                'title' => $products[$id]['title'],
                'url'   => $products[$id]['productUrl'],
                'uid'   => $products[$id]['uid'],
                'time'  => $products[$id]['time_create'],
            );
        }

        if (is_scalar($item)) {
            $result = $result[$item];
        }

        return $result;
    }

    /**
     * Locate source id via route
     *
     * @param RouteMatch|array $params
     *
     * @return mixed|bool
     */
    public function locate($params = null)
    {
        if (null == $params) {
            $params = Pi::engine()->application()->getRouteMatch();
        }
        if ($params instanceof RouteMatch) {
            $params = $params->getParams();
        }
        if ('shop' == $params['module']
            && !empty($params['slug'])
        ) {
            $product = Pi::api('product', 'shop')->getProductLight($params['slug'], 'slug');
            $item = $product['id'];
        } else {
            $item = false;
        }
        return $item;
    }
}
