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

namespace Module\Shop\Controller\Front;

use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Laminas\Db\Sql\Predicate\Expression;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);


        // Check homepage type
        switch ($config['homepage_type']) {
            default:
            case 'list':
                // Save statistics
                if (Pi::service('module')->isActive('statistics')) {
                    Pi::api('log', 'statistics')->save('shop', 'home-product');
                }

                // category list
                $categoriesJson = Pi::api('category', 'shop')->categoryListJson();

                // Set view
                $this->view()->setTemplate('product-angular');
                $this->view()->assign('config', $config);
                $this->view()->assign('categoriesJson', $categoriesJson);
                $this->view()->assign('pageType', 'all');
                break;

            case 'widget':
                // Save statistics
                if (Pi::service('module')->isActive('statistics')) {
                    Pi::api('log', 'statistics')->save('shop', 'home-widget');
                }

                // Set title
                $title = (!empty($config['homepage_title'])) ? $config['homepage_title'] : __('Shop index');

                // category list
                $categoriesJson = Pi::api('category', 'shop')->categoryListJson();

                // Set view
                $this->view()->setTemplate('homepage-widget');
                $this->view()->assign('config', $config);
                $this->view()->assign('categoriesJson', $categoriesJson);
                $this->view()->assign('productTitleH1', $title);
                $this->view()->assign('isHomepage', 1);
                break;

            case 'brand':
                // Save statistics
                if (Pi::service('module')->isActive('statistics')) {
                    Pi::api('log', 'statistics')->save('shop', 'home-brand');
                }

                // Set params
                $params = [
                    'type'   => 'brand',
                    'parent' => 0,
                ];

                // Get brands
                $brandList = Pi::api('category', 'shop')->categoryList($params);

                // Set title
                $title = (!empty($config['homepage_title'])) ? $config['homepage_title'] : __('Our brands');

                // Set view
                $this->view()->setTemplate('homepage-brand');
                $this->view()->assign('config', $config);
                $this->view()->assign('brandList', $brandList);
                $this->view()->assign('productTitleH1', $title);
                $this->view()->assign('isHomepage', 1);
                break;

            case 'category':
                // Save statistics
                if (Pi::service('module')->isActive('statistics')) {
                    Pi::api('log', 'statistics')->save('shop', 'home-category');
                }

                // Set params
                $params = [
                    'type'   => 'category',
                    'parent' => 0,
                ];

                // Get brands
                $categoryList = Pi::api('category', 'shop')->categoryList($params);

                // Set title
                $title = (!empty($config['homepage_title'])) ? $config['homepage_title'] : __('Our categories');

                // Set view
                $this->view()->setTemplate('homepage-category');
                $this->view()->assign('config', $config);
                $this->view()->assign('categoryList', $categoryList);
                $this->view()->assign('productTitleH1', $title);
                $this->view()->assign('isHomepage', 1);
                break;
        }
    }

    public function productList($where, $limit = '')
    {
        // Set info
        $product   = [];
        $productId = [];
        $page      = $this->params('page', 1);
        $sort      = $this->params('sort', 'create');
        $stock     = $this->params('stock');
        $offset    = (int)($page - 1) * $this->config('view_perpage');
        $limit     = empty($limit) ? intval($this->config('view_perpage')) : $limit;
        $order     = $this->setOrder($sort);

        // Set show just have stock
        if (isset($stock) && $stock == 1) {
            $where['stock'] = 1;
        }

        // Set info
        $columns = ['product' => new Expression('DISTINCT product'), '*'];

        // Get info from link table
        $select = $this->getModel('link')->select()->where($where)->columns($columns)->order($order)->offset($offset)->limit($limit);
        $rowSet = $this->getModel('link')->selectWith($select);

        // Make list
        if (!empty($rowSet)) {
            $rowSet = $rowSet->toArray();
            foreach ($rowSet as $id) {
                $productId[] = $id['product'];
            }
        }

        // Set info
        if (!empty($productId)) {
            $where = ['status' => 1, 'id' => $productId];

            // Get list of product
            $select = $this->getModel('product')->select()->where($where)->order($order);
            $rowSet = $this->getModel('product')->selectWith($select);
            foreach ($rowSet as $row) {
                $product[$row->id] = Pi::api('product', 'shop')->canonizeProductLight($row);
            }
        }

        // return product
        return $product;
    }

    public function productJsonList($where)
    {
        // Set info
        $product = [];
        $limit   = 150;
        $page    = $this->params('page', 1);
        $offset  = (int)($page - 1) * $limit;
        $order   = ['time_update ASC'];

        // Set info
        $columns = ['product' => new Expression('DISTINCT product'), '*'];

        // Get info from link table
        $select = $this->getModel('link')->select()->where($where)->columns($columns)->order($order)->offset($offset)->limit($limit);
        $rowSet = $this->getModel('link')->selectWith($select);


        // Make list
        if (!empty($rowSet)) {
            $rowSet = $rowSet->toArray();
            foreach ($rowSet as $id) {
                $productId[] = $id['product'];
            }
        }

        if (empty($productId)) {
            return $product;
        }

        // Set info
        $where = ['status' => 1, 'id' => $productId];

        // Get list of product
        $select = $this->getModel('product')->select()->where($where)->order($order);
        $rowSet = $this->getModel('product')->selectWith($select);

        foreach ($rowSet as $row) {
            $product[] = Pi::api('product', 'shop')->canonizeProductJson($row);
        }

        // return product
        return $product;
    }

    public function setOrder($sort = 'create')
    {
        // Set order
        switch ($sort) {
            case 'stock':
                $order = ['stock DESC', 'id DESC'];
                break;

            case 'price':
                $order = ['price DESC', 'id DESC'];
                break;

            case 'update':
                $order = ['time_update DESC', 'id DESC'];
                break;

            case 'create':
            default:
                $order = ['time_create DESC', 'id DESC'];
                break;
        }
        return $order;
    }
}