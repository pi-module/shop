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
use Pi\Application\Api\AbstractApi;
use Zend\Db\Sql\Predicate\Expression;

/*
 * Pi::api('api', 'shop')->productList($params)
 * Pi::api('api', 'shop')->categoryList()
 * Pi::api('api', 'shop')->viewPrice($price);
 */

class Api extends AbstractApi
{
    public function productList($params)
    {}

    public function categoryList()
    {
        $category = [];

        $where  = ['status' => 1, 'type' => 'category'];
        $order  = ['title ASC', 'id DESC'];
        $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('category', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $category[]     = [
                'id'        => $categorySingle['id'],
                'slug'      => $categorySingle['slug'],
                'parent'    => $categorySingle['parent'],
                'title'     => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl'  => $categorySingle['thumbUrl'],
            ];
        }

        $category = Pi::api('category', 'shop')->makeTree($category);

        // Get count
        $columnsCount = ['count' => new Expression('count(*)')];
        $select       = Pi::model('category', $this->getModule())->select()->where($where)->columns($columnsCount);
        $count        = Pi::model('category', $this->getModule())->selectWith($select)->current()->count;

        // Set result
        $result = [
            'categories' => $category,
            'paginator'  => [
                'count' => $count,
            ],
        ];

        return $result;
    }

    public function viewPrice($price)
    {
        if (Pi::service('module')->isActive('order')) {
            // Load language
            Pi::service('i18n')->load(['module/order', 'default']);
            // Set price
            $viewPrice = Pi::api('api', 'order')->viewPrice($price);
        } else {
            $viewPrice = _currency($price);
        }
        return $viewPrice;
    }
}