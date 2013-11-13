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
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Zend\Json\Json;

class IndexController extends ActionController
{
	public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set product info
        $where = array('status' => 1);
        // Get product List
        $product = $this->productList($where);
        // Set paginator info
        $template = array(
            'controller' => 'index',
            'action' => 'index',
            );
        // Get paginator
        $paginator = $this->productPaginator($template, $where);
        // Set view
    	$this->view()->setTemplate('product_list');
        $this->view()->assign('products', $product);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
    }

    public function productList($where)
    {
        // Set info
        $id = array();
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
        $columns = array('product' => new \Zend\Db\Sql\Expression('DISTINCT product'));
        // Get info from link table
        $select = $this->getModel('link')->select()->where($where)->columns($columns)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('link')->selectWith($select)->toArray();
        // Make list
        foreach ($rowset as $id) {
            $productId[] = $id['product'];
        }
        // Set info
        $where = array('status' => 1, 'id' => $productId);
        // Get list of product
        $select = $this->getModel('product')->select()->where($where)->order($order);
        $rowset = $this->getModel('product')->selectWith($select);
        $product = $this->canonizeProduct($rowset);
        // return product
        return $product;
    }

    public function searchList($where)
    {
        // Set info
        $id = array();
        $page = $this->params('page', 1);
        $module = $this->params('module');
        $sort = $this->params('sort', 'create');
        $stock = $this->params('stock');
        $offset = (int)($page - 1) * $this->config('view_perpage');
        $limit = intval($this->config('view_perpage'));
        $order = $this->setOrder($sort);
        // Set show just have stock
        if (isset($stock) && $stock == 1) {
            $where['stock'] > 0;
        }
        // Get list of product
        $select = $this->getModel('product')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('product')->selectWith($select);
        $product = $this->canonizeProduct($rowset);
        // return product
        return $product;   
    }

    public function productPaginator($template, $where)
    {
        $template['module'] = $this->params('module');
        $template['sort'] = $this->params('sort');
        $template['stock'] = $this->params('stock');
        $template['page'] = $this->params('page', 1);
        // get count     
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(DISTINCT `product`)'));
        $select = $this->getModel('link')->select()->where($where)->columns($columns);
        $template['count'] = $this->getModel('link')->selectWith($select)->current()->count;
        // paginator
        $paginator = $this->canonizePaginator($template);
        return $paginator;
    }

    public function searchPaginator($template, $where)
    {
        $template['module'] = $this->params('module');
        $template['sort'] = $this->params('sort');
        $template['stock'] = $this->params('stock');
        $template['page'] = $this->params('page', 1);
        // get count     
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = $this->getModel('product')->select()->where($where)->columns($columns);
        $template['count'] = $this->getModel('product')->selectWith($select)->current()->count;
        // paginator
        $paginator = $this->canonizePaginator($template);
        return $paginator;
    }

    public function canonizeProduct($rowset)
    {
        $module = $this->params('module');
        // Get category list
        $categoryList = Pi::api('shop', 'category')->categoryList();
        // Set product
        $product = array();
        // Make list
        foreach ($rowset as $row) {
            $product[$row->id] = $row->toArray();
            $product[$row->id]['summary'] = Pi::service('markup')->render($product[$row->id]['summary'], 'text', 'html');
            $product[$row->id]['description'] = Pi::service('markup')->render($product[$row->id]['description'], 'text', 'html');
            $product[$row->id]['time_create_view'] = _date($product[$row->id]['time_create']);
            $product[$row->id]['time_update_view'] = _date($product[$row->id]['time_update']);
            // Set product url
            $product[$row->id]['link'] = $this->url('', array(
                    'module'        => $module,
                    'controller'    => 'product',
                    'slug'          => $product[$row->id]['slug'],
                ));
            // Set category information
            $productCategory = Json::decode($product[$row->id]['category']);
            foreach ($productCategory as $category) {
                $product[$row->id]['categories'][$category]['title'] = $categoryList[$category]['title'];
                $product[$row->id]['categories'][$category]['slug'] = $categoryList[$category]['slug'];
                $product[$row->id]['categories'][$category]['url'] = $this->url('', array(
                    'module'        => $module,
                    'controller'    => 'category',
                    'slug'          => $categoryList[$category]['slug'],
                ));
            }
            // Set image url
            if ($product[$row->id]['image']) {
                $product[$row->id]['originalUrl'] = Pi::url(sprintf('upload/%s/original/%s/%s', $this->config('image_path'), $product[$row->id]['path'], $product[$row->id]['image']));
                $product[$row->id]['largeUrl'] = Pi::url(sprintf('upload/%s/large/%s/%s', $this->config('image_path'), $product[$row->id]['path'], $product[$row->id]['image']));
                $product[$row->id]['mediumUrl'] = Pi::url(sprintf('upload/%s/medium/%s/%s', $this->config('image_path'), $product[$row->id]['path'], $product[$row->id]['image']));
                $product[$row->id]['thumbUrl'] = Pi::url(sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $product[$row->id]['path'], $product[$row->id]['image']));
            }
        }
        // return product
        return $product; 
    }

    public function canonizePaginator($template)
    {
        // paginator
        $paginator = Paginator::factory(intval($template['count']));
        $paginator->setItemCountPerPage(intval($this->config('view_perpage')));
        $paginator->setCurrentPageNumber(intval($template['page']));
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => $template['controller'],
                'action'        => $template['action'],
                'sort'          => $template['sort'],
                'stock'         => $template['stock'],
            )),
        ));
        return $paginator;
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