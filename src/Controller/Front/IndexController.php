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

class IndexController extends ActionController
{
	public function indexAction()
    {
        // Get info from url
        $page = $this->params('page', 1);
        $module = $this->params('module');


        $test = $this->url('', array(
                    'module'        => 'shop',
                    'controller'    => 'product',
                    'action'        => 'index',
                    'slug'          => 'dasdasd-sadad-asdasd-sdsd',
                    'page'          => '2',
                    'sort'          => 'price',
                    'order'         => 'desc',
                    'stock'         => '1',
                ));

    	$this->view()->setTemplate('product_list');
        $this->view()->assign('test', $test);
    }

    public function productList($where, $offset, $limit, $order)
    {
        $id = array();
        $product = array();
        $module = $this->params('module');
        // Get category list
        $categoryList = array();
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
            $productCategory = Json::decode($product[$row->id]['category']);
            foreach ($productCategory as $category) {
                $product[$row->id]['categories'][$category]['title'] = $categoryList[$category]['title'];
                $product[$row->id]['categories'][$category]['slug'] = $categoryList[$category]['slug'];
            }
            $product[$row->id]['summary'] = Pi::service('markup')->render($product[$row->id]['summary'], 'text', 'html');
            $product[$row->id]['time_create'] = _date($product[$row->id]['time_create']);
            $product[$row->id]['time_update'] = _date($product[$row->id]['time_update']);
            $product[$row->id]['link'] = '';
            if ($$product[$row->id]['image']) {

            }
        }
        // return product
        return $product;
    }
    
    public function productPaginator($template, $where, $page, $perpage)
    {
        // get count     
        $columns = array('count' => new \Zend\Db\Sql\Expression('count(DISTINCT `story`)'));
        $select = $this->getModel('link')->select()->where($where)->columns($columns);
        $count = $this->getModel('link')->selectWith($select)->current()->count;
        // paginator
        $paginator = \Pi\Paginator\Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($perpage);
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array('template' => $this->url('.news', $template)));
        return $paginator;
    }
}