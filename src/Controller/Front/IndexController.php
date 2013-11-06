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
        $page = $this->params('page', 1);
        $module = $this->params('module');
        // Set product info
        $where = array('status' => 1);
        $offset = (int)($page - 1) * $this->config('view_perpage');
        $limit = intval($this->config('view_perpage'));
        $order = array('time_create DESC', 'id DESC');
        // Get product List
        $product = $this->productList($where, $offset, $limit, $order);
        // Set paginator info
        $template = array(
            'controller' => 'index',
            'action' => 'index',
            );
        // Get paginator
        $paginator = $this->productPaginator($template, $where, $page, $this->config('view_perpage'));
        // Set view
    	$this->view()->setTemplate('product_list');
        $this->view()->assign('products', $product);
        $this->view()->assign('paginator', $paginator);
    }

    public function productList($where, $offset, $limit, $order)
    {
        $id = array();
        $product = array();
        $module = $this->params('module');
        // Get category list
        $categoryList = Pi::api('shop', 'category')->categoryList();
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
        // Make list
        foreach ($rowset as $row) {
            $product[$row->id] = $row->toArray();
            $product[$row->id]['summary'] = Pi::service('markup')->render($product[$row->id]['summary'], 'text', 'html');
            $product[$row->id]['description'] = Pi::service('markup')->render($product[$row->id]['description'], 'text', 'html');
            $product[$row->id]['time_create'] = _date($product[$row->id]['time_create']);
            $product[$row->id]['time_update'] = _date($product[$row->id]['time_update']);
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
    
    public function productPaginator($template, $where, $page, $perpage)
    {
        // get count     
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(DISTINCT `product`)'));
        $select = $this->getModel('link')->select()->where($where)->columns($columns);
        $count = $this->getModel('link')->selectWith($select)->current()->count;
        // paginator
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($perpage);
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router'    => $this->getEvent()->getRouter(),
            'route'     => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params'    => array_filter(array(
                'module'        => $this->getModule(),
                'controller'    => $template['controller'],
                'action'        => $template['action'],
            )),
        ));
        return $paginator;
    }
}