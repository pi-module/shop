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
use Zend\Json\Json;
use Zend\Db\Sql\Predicate\Expression;

class JsonController extends IndexController
{
    public function indexAction()
    {
        // Set return
        $return = array(
            'website' => Pi::url(),
            'module' => $this->params('module'),
        );
        // Set view
        return $return;
    }

    public function productAllAction()
    {
        // Get info from url
        $id = $this->params('id', 0);
        $update = $this->params('update', 0);
        // Check password
        if (!$this->checkPassword()) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('Password not set or wrong'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Check
        $where = array(
            'status' => 1,
            'time_update > ?' => $update,
        );
        // Get story List
        $productList = $this->productJsonList($where);
        // Set view
        return $productList;
    }

    public function productCategoryAction()
    {
        // Get info from url
        $categoryMain = $this->params('id');
        $update = $this->params('update', 0);
        // Check password
        if (!$this->checkPassword()) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('Password not set or wrong'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get category information from model by category_main id
        $categoryMain = $this->getModel('category')->find($categoryMain);
        $categoryMain = Pi::api('category', 'shop')->canonizeCategory($categoryMain);
        // Check category
        if (!$categoryMain || $categoryMain['status'] != 1) {
            $productList = array();
        } else {
            // Set story info
            $where = array(
                'status' => 1,
                'category' => $categoryMain['id'],
                'time_update > ?' => $update,
            );
            // Get story List
            $productList = $this->productJsonList($where);
        }
        // Set view
        return $productList;
    }

    public function productSingleAction()
    {
        // Get info from url
        $id = $this->params('id');
        $module = $this->params('module');
        // Check password
        if (!$this->checkPassword()) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('Password not set or wrong'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Find product
        $product = $this->getModel('product')->find($id);
        $product = Pi::api('product', 'shop')->canonizeProductJson($product);
        // Check item
        if (!$product || $product['status'] != 1) {
            $productSingle = array();
        } else {
            $productSingle = $product;
            if ($product['attribute'] && $config['view_attribute']) {
                $attributes = Pi::api('attribute', 'shop')->Product($product['id']);
                //$productSingle['attributes'] = $attributes['all'];
                foreach ($attributes['all'] as $attribute) {
                    $productSingle['attribute-' . $attribute['id']] = $attribute['data'];
                }
            }
        }
        $productSingle = array($productSingle);
        // Set view
        return $productSingle;
    }

    /* public function questionAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set view
        $this->view()->setTemplate(false)->setLayout('layout-content');
        // Check post
        if ($this->request->isPost() && $config['view_question']) {
            // Get from post
            $data = $this->request->getPost();
            $data = $data->toArray();
            // Check notification module
            if (Pi::service('module')->isActive('notification')) {
                // Get admin main
                $adminmail = Pi::config('adminmail');
                $adminname = Pi::config('adminname');

                // Set mail information
                $information = array(
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'question' => $data['question'],
                    'id' => $data['id'],
                    'title' => $data['title'],
                );

                // Set toAdmin
                $toAdmin = array(
                    $adminmail => $adminname,
                );

                // Send mail to admin
                Pi::api('mail', 'notification')->send(
                    $toAdmin,
                    'user_question',
                    $information,
                    Pi::service('module')->current()
                );
            }

            // back
            $message = __('Your question send to admin');
            $this->jump($data['back'], $message);
        }
    } */

    public function filterIndexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get search form
        $filterList = Pi::api('attribute', 'shop')->filterList();
        $categoryList = Pi::registry('categoryList', 'shop')->read();
        // Set info
        $product = array();
        $where = array(
            'status' => 1,
        );
        $order = array('recommended DESC', 'time_create DESC', 'id DESC');
        $columns = array('product' => new Expression('DISTINCT product'));
        // Get info from link table
        $select = $this->getModel('link')->select()->where($where)->columns($columns)->order($order);
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
            $product[] = Pi::api('product', 'shop')->canonizeProductFilter($row, $categoryList, $filterList);
        }
        // Set view
        return $product;
    }

    public function filterCategoryAction()
    {
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category information from model
        $category = $this->getModel('category')->find($slug, 'slug');
        $category = Pi::api('category', 'shop')->canonizeCategory($category, 'compact');
        // Check category
        if (!$category || $category['status'] != 1) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The category not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get search form
        $filterList = Pi::api('attribute', 'shop')->filterList();
        $categoryList = Pi::registry('categoryList', 'shop')->read();
        // category list
        $categories = Pi::api('category', 'shop')->categoryList($category['id']);
        // Get id list
        $idList = array();
        $idList[] = $category['id'];
        foreach ($categories as $singleCategory) {
            $idList[] = $singleCategory['id'];
        }
        // Set info
        $product = array();
        $where = array(
            'status' => 1,
            'category' => $idList,
        );
        $order = array('recommended DESC', 'time_create DESC', 'id DESC');
        $columns = array('product' => new Expression('DISTINCT product'));
        // Get info from link table
        $select = $this->getModel('link')->select()->where($where)->columns($columns)->order($order);
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
            $product[] = Pi::api('product', 'shop')->canonizeProductFilter($row, $categoryList, $filterList);
        }
        // Set view
        return $product;
    }

    public function filterTagAction()
    {
        // Check tag
        if (!Pi::service('module')->isActive('tag')) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('Tag module not installed.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check slug
        if (!isset($slug) || empty($slug)) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The tag not set.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get id from tag module
        $tagId = array();
        $tags = Pi::service('tag')->getList($slug, $module);
        foreach ($tags as $tag) {
            $tagId[] = $tag['item'];
        }
        // Check slug
        if (empty($tagId)) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The tag not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get search form
        $filterList = Pi::api('attribute', 'shop')->filterList();
        $categoryList = Pi::registry('categoryList', 'shop')->read();
        // Set info
        $where = array('status' => 1, 'id' => $tagId);
        $order = array('recommended DESC', 'time_create DESC', 'id DESC');
        // Get list of product
        $select = $this->getModel('product')->select()->where($where)->order($order);
        $rowset = $this->getModel('product')->selectWith($select);
        foreach ($rowset as $row) {
            $product[] = Pi::api('product', 'shop')->canonizeProductFilter($row, $categoryList, $filterList);
        }
        // Set view
        return $product;
    }

    public function filterSearchAction() {
        // Get info from url
        $module = $this->params('module');
        $keyword = $this->params('keyword');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check keyword not empty
        if (empty($keyword)) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The keyword not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Set list
        $list = array();
        // Set info
        $where = array('status' => 1);
        $where['title LIKE ?'] = '%' . $keyword . '%';
        $order = array('recommended DESC', 'time_create DESC', 'id DESC');
        // Item list header
        $list[] = array(
            'class' => ' class="dropdown-header"',
            'title' => sprintf(__('Products related to %s'), $keyword),
            'url' => '#',
            'image' => Pi::service('asset')->logo(),
        );
        // Get list of product
        $select = $this->getModel('product')->select()->where($where)->order($order)->limit(10);
        $rowset = $this->getModel('product')->selectWith($select);
        foreach ($rowset as $row) {
            $product = Pi::api('product', 'shop')->canonizeProductLight($row);
            $list[] = array(
                'class' => '',
                'title' => $product['title'],
                'url' => $product['productUrl'],
                'image' =>  $product['thumbUrl'],
            );
        }
        // Location list header
        $list[] = array(
            'class' => ' class="dropdown-header"',
            'title' => sprintf(__('Categories related to %s'), $keyword),
            'url' => '#',
            'image' => Pi::service('asset')->logo(),
        );
        // Get list of categories
        $select = $this->getModel('category')->select()->where($where)->order($order)->limit(5);
        $rowset = $this->getModel('category')->selectWith($select);
        foreach ($rowset as $row) {
            $category = Pi::api('category', 'shop')->canonizeCategory($row);
            $list[] = array(
                'class' => '',
                'title' => $category['title'],
                'url' => $category['categoryUrl'],
                'image' => isset($category['thumbUrl']) ? $category['thumbUrl'] : Pi::service('asset')->logo(),
            );
        }
        // Set view
        return $list;
    }

    public function checkPassword() {
        // Get info from url
        $module = $this->params('module');
        $password = $this->params('password');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check password
        if ($config['json_check_password']) {
            if ($config['json_password'] == $password) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }
}