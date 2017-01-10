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

namespace Module\Shop\Controller\Front;

use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Zend\Db\Sql\Predicate\Expression;

class IndexController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // category list
        $categoriesJson = Pi::api('category', 'shop')->categoryListJson();
        // Check homepage type
        switch ($config['homepage_type']) {
            default:
            case 'list':
                $this->view()->setTemplate('product-angular');
                $this->view()->assign('config', $config);
                $this->view()->assign('categoriesJson', $categoriesJson);
                $this->view()->assign('pageType', 'all');
                break;

            case 'brand':
                // Set title
                $title = (!empty($config['homepage_title'])) ? $config['homepage_title'] : __('Shop index');
                // Set view
                $this->view()->setTemplate('homepage');
                $this->view()->assign('config', $config);
                $this->view()->assign('categoriesJson', $categoriesJson);
                $this->view()->assign('productTitleH1', $title);
                $this->view()->assign('isHomepage', 1);
                break;
        }
    }

    public function productList($where)
    {
        // Set info
        $product = array();
        $productId = array();
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $sort = $this->params('sort', 'create');
        $stock = $this->params('stock');
        $offset = (int)($page - 1) * $this->config('view_perpage');
        $limit = intval($this->config('view_perpage'));
        $order = $this->setOrder($sort);
        // Set show just have stock
        if (isset($stock) && $stock == 1) {
            $where['stock'] = 1;
        }
        // Set info
        $columns = array('product' => new Expression('DISTINCT product'));
        // Get info from link table
        $select = $this->getModel('link')->select()->where($where)->columns($columns)
            ->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('link')->selectWith($select)->toArray();
        // Make list
        foreach ($rowset as $id) {
            $productId[] = $id['product'];
        }
        // Set info
        if (!empty($productId)) {
            $where = array('status' => 1, 'id' => $productId);
            // Get list of product
            $select = $this->getModel('product')->select()->where($where)->order($order);
            $rowset = $this->getModel('product')->selectWith($select);
            foreach ($rowset as $row) {
                $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row);
            }
        }
        // return product
        return $product;
    }

    public function productJsonList($where)
    {
        // Set info
        $product = array();
        $limit = 150;
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $offset = (int)($page - 1) * $limit;
        $order = array('time_update ASC');
        // Set info
        $columns = array('product' => new Expression('DISTINCT product'));
        // Get info from link table
        $select = $this->getModel('link')->select()->where($where)->columns($columns)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('link')->selectWith($select)->toArray();
        // Make list
        foreach ($rowset as $id) {
            $productId[] = $id['product'];
        }
        if (empty($productId)) {
            return $product;
        }
        // Set info
        $where = array('status' => 1, 'id' => $productId);
        // Get list of product
        $select = $this->getModel('product')->select()->where($where)->order($order);
        $rowset = $this->getModel('product')->selectWith($select);
        foreach ($rowset as $row) {
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
                $order = array('stock DESC', 'id DESC');
                break;

            case 'price':
                $order = array('price DESC', 'id DESC');
                break;

            case 'update':
                $order = array('time_update DESC', 'id DESC');
                break;

            case 'create':
            default:
                $order = array('time_create DESC', 'id DESC');
                break;
        }
        return $order;
    }
}