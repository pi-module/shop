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
use Zend\Db\Sql\Predicate\Expression;

class JsonController extends IndexController
{
    /* public function indexAction()
    {
        // Set return
        $return = array(
            'website' => Pi::url(),
            'module' => $this->params('module'),
        );
        // Set view
        return $return;
    } */

    public function searchAction()
    {
        // Get info from url
        $module = $this->params('module');
        $page = $this->params('page', 1);
        $category = $this->params('category');
        $tag = $this->params('tag');
        $title = $this->params('title');

        // Set has search result
        $hasSearchResult = true;

        // Clean title
        if (Pi::service('module')->isActive('search') && isset($title) && !empty($title)) {
            $title = Pi::api('api', 'search')->parseQuery($title);
        } elseif (isset($title) && !empty($title)) {
            $title = _strip($title);
        } else {
            $title = '';
        }

        // Clean params
        $paramsClean = array();
        foreach ($_GET as $key => $value) {
            $key = _strip($key);
            $value = _strip($value);
            $paramsClean[$key] = $value;
        }

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set empty result
        $result = array(
            'products' => array(),
            //'category' => array(),
            'filterList' => array(),
            'paginator' => array(),
            'condition' => array(),
            'price' => array(),
        );

        // Get category information from model
        if (!empty($category)) {
            // Get category
            $category = Pi::api('category', 'shop')->getCategory($category, 'slug');
            // Check category
            if (!$category || $category['status'] != 1) {
                return $result;
            }
            // category list
            $categories = Pi::api('category', 'shop')->categoryList($category['id']);
            // Get id list
            $categoryIDList = array();
            $categoryIDList[] = $category['id'];
            foreach ($categories as $singleCategory) {
                $categoryIDList[] = $singleCategory['id'];
            }
        }

        // Get search form
        $filterList = Pi::api('attribute', 'shop')->filterList();
        $categoryList = Pi::registry('categoryList', 'shop')->read();

        // Set product ID list
        $checkTitle = false;
        $checkAttribute = false;
        $productIDList = array(
            'title' => array(),
            'attribute' => array(),
        );

        // Check title from product table
        if (isset($title) && !empty($title)) {
            $checkTitle = true;
            $titles = is_array($title) ? $title : array($title);
            $order = array('recommended DESC', 'time_create DESC', 'id DESC');
            $columns = array('id');
            $select = $this->getModel('product')->select()->columns($columns)->where(function ($where) use ($titles) {
                $whereMain = clone $where;
                $whereKey = clone $where;
                $whereMain->equalTo('status', 1);
                foreach ($titles as $title) {
                    $whereKey->like('title', '%' . $title . '%')->and;
                }
                $where->andPredicate($whereMain)->andPredicate($whereKey);
            })->order($order);
            $rowset = $this->getModel('product')->selectWith($select);
            foreach ($rowset as $row) {
                $productIDList['title'][$row->id] = $row->id;
            }
        }

        // Check attribute
        if (!empty($paramsClean)) {
            // Make attribute list
            $attributeList = array();
            foreach ($filterList as $filterSingle) {
                if (isset($paramsClean[$filterSingle['name']]) && !empty($paramsClean[$filterSingle['name']])) {
                    $attributeList[$filterSingle['name']] = array(
                        'field' => $filterSingle['id'],
                        'data' => $paramsClean[$filterSingle['name']],
                    );
                }
            }
            // Search on attribute
            if (!empty($attributeList)) {
                $checkAttribute = true;
                $column = array('product');
                foreach ($attributeList as $attributeSingle) {
                    $where = array(
                        'field' => $attributeSingle['field'],
                        'data' => $attributeSingle['data'],
                    );
                    $select = $this->getModel('field_data')->select()->where($where)->columns($column);
                    $rowset = $this->getModel('field_data')->selectWith($select);
                    foreach ($rowset as $row) {
                        $productIDList['attribute'][$row->product] = $row->product;
                    }
                }
            }
        }

        // Set info
        $product = array();
        $count = 0;

        $order = array('recommended DESC', 'time_create DESC', 'id DESC');
        $columns = array('product' => new Expression('DISTINCT product'));
        $offset = (int)($page - 1) * $config['view_perpage'];
        $limit = intval($config['view_perpage']);
        // Set where link
        $whereLink = array('status' => 1);
        if (isset($categoryIDList) && !empty($categoryIDList)) {
            $whereLink['category'] = $categoryIDList;
        }
        if ($checkTitle && $checkAttribute) {
            if (!empty($productIDList['title']) && !empty($productIDList['attribute'])) {
                $whereLink['product'] = array_intersect($productIDList['title'], $productIDList['attribute']);
            } else {
                $hasSearchResult = false;
            }
        } elseif ($checkTitle) {
            if (!empty($productIDList['title'])) {
                $whereLink['product'] = $productIDList['title'];
            } else {
                $hasSearchResult = false;
            }
        } elseif ($checkAttribute) {
            if (!empty($productIDList['attribute'])) {
                $whereLink['product'] = $productIDList['attribute'];
            } else {
                $hasSearchResult = false;
            }
        }

        // Get max price
        $columnsPrice = array('id', 'price');
        $limitPrice = 1;
        $maxPrice = 1000;
        $minPrice = 0;

        $orderPrice = array('price DESC', 'id DESC');
        $selectPrice = $this->getModel('link')->select()->where($whereLink)->columns($columnsPrice)->order($orderPrice)->limit($limitPrice);
        $rowPrice = $this->getModel('link')->selectWith($selectPrice)->current();
        if ($rowPrice) {
            $rowPrice = $rowPrice->toArray();
            $maxPrice = $rowPrice['price'];
        }

        $orderPrice = array('price ASC', 'id ASC');
        $selectPrice = $this->getModel('link')->select()->where($whereLink)->columns($columnsPrice)->order($orderPrice)->limit($limitPrice);
        $rowPrice = $this->getModel('link')->selectWith($selectPrice)->current();
        if ($rowPrice) {
            $rowPrice = $rowPrice->toArray();
            $minPrice = $rowPrice['price'];
        }

        // Get select min price
        $minSelect = $minPrice;
        if (isset($paramsClean['minPrice']) && !empty($paramsClean['minPrice'])) {
            $minSelect = $paramsClean['minPrice'];
            if ($minSelect > $minPrice) {
                $whereLink['price >= ?'] = $minSelect;
            }
        }

        // Get select max price
        $maxSelect = $maxPrice;
        if (isset($paramsClean['maxPrice']) && !empty($paramsClean['maxPrice'])) {
            $maxSelect = $paramsClean['maxPrice'];
            if ($maxSelect < $maxPrice) {
                $whereLink['price <= ?'] = $maxSelect;
            }
        }

        // Check has Search Result
        if ($hasSearchResult) {
            // Get info from link table
            $select = $this->getModel('link')->select()->where($whereLink)->columns($columns)->order($order)->offset($offset)->limit($limit);
            $rowset = $this->getModel('link')->selectWith($select)->toArray();
            foreach ($rowset as $id) {
                $productIDSelect[] = $id['product'];
            }

            // Get list of product
            if (!empty($productIDSelect)) {
                $where = array('status' => 1, 'id' => $productIDSelect);
                $select = $this->getModel('product')->select()->where($where)->order($order);
                $rowset = $this->getModel('product')->selectWith($select);
                foreach ($rowset as $row) {
                    $product[] = Pi::api('product', 'shop')->canonizeProductFilter($row, $categoryList, $filterList);
                }
            }

            // Get count
            $columnsCount = array('count' => new Expression('count(DISTINCT `product`)'));
            $select = $this->getModel('link')->select()->where($whereLink)->columns($columnsCount);
            $count = $this->getModel('link')->selectWith($select)->current()->count;
        }

        // Set result
        $result = array(
            //'paramsClean' => $paramsClean,
            //'whereLink' => $whereLink,
            //'categoryIDList' => $categoryIDList,
            //'productIDList' => $productIDList,

            'products' => $product,
            //'category' => $category,
            //'tag' => $tag,
            'filterList' => $filterList,
            'paginator' => array(
                'count' => $count,
                'limit' => intval($config['view_perpage']),
                'page' => $page,
            ),
            'condition' => array(
                'title' => __('New products'),
                'urlCompare' => Pi::url($this->url('', array('controller' => 'compare'))),
            ),
            'price' => array(
                'minValue' => intval($minPrice),
                'maxValue' => intval($maxPrice),
                'minSelect' => intval($minSelect),
                'maxSelect' => intval($maxSelect),
                'step' => intval(($maxPrice - $minPrice) / 10),
                'rightToLeft' => true,
            ),
        );

        return $result;
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

    /* public function filterIndexAction()
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
    } */

    /* public function filterCategoryAction()
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
    } */

    /* public function filterTagAction()
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
    } */

    /* public function filterSearchAction() {
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
    } */

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