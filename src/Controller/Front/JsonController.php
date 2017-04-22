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
    public function searchAction()
    {
        // Get info from url
        $module = $this->params('module');
        $page = $this->params('page', 1);
        $title = $this->params('title');
        $category = $this->params('category');
        $tag = $this->params('tag');
        $favourite = $this->params('favourite');
        $recommended = $this->params('recommended');
        $limit = $this->params('limit');
        $order = $this->params('order');

        // Set has search result
        $hasSearchResult = true;

        // Clean title
        if (Pi::service('module')->isActive('search') && isset($title) && !empty($title)) {
            $title = Pi::api('api', 'search')->parseQuery(urldecode($title));
        } elseif (isset($title) && !empty($title)) {
            $title = _strip(urldecode($title));
        } else {
            $title = '';
        }

        // Clean params
        $paramsClean = array();
        foreach ($_GET as $key => $value) {
            $key = _strip(urldecode($key));
            $value = _strip(urldecode($value));
            $paramsClean[$key] = $value;
        }

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set empty result
        $result = array(
            'products' => array(),
            'filterList' => array(),
            'paginator' => array(),
            'condition' => array(),
            'price' => array(),
        );

        // Set where link
        $whereLink = array('status' => 1);
        if (!empty($recommended) && $recommended == 1) {
            $whereLink['recommended'] = 1;
        }

        // Set page title
        $pageTitle = __('List of products');

        // Set order
        switch ($order) {
            case 'title':
                $order = array('title DESC', 'id DESC');
                break;

            case 'titleASC':
                $order = array('title ASC', 'id ASC');
                break;

            case 'hits':
                $order = array('hits DESC', 'id DESC');
                break;

            case 'hitsASC':
                $order = array('hits ASC', 'id ASC');
                break;

            case 'create':
                $order = array('time_create DESC', 'id DESC');
                break;

            case 'createASC':
                $order = array('time_create ASC', 'id ASC');
                break;

            case 'update':
                $order = array('time_update DESC', 'id DESC');
                break;

            case 'updateASC':
                $order = array('time_update ASC', 'id ASC');
                break;

            case 'recommended':
                $order = array('recommended DESC', 'time_create DESC', 'id DESC');
                break;

            case 'price':
                $order = array('price DESC', 'id DESC');
                break;

            case 'priceASC':
                $order = array('price ASC', 'id ASC');
                break;

            case 'stock':
                $order = array('stock DESC', 'id DESC');
                break;

            case 'stockASC':
                $order = array('stock ASC', 'id ASC');
                break;

            case 'sold':
                $order = array('sold DESC', 'id DESC');
                break;

            default:
                $order = array('time_create DESC', 'id DESC');
                break;
        }

        // Get category information from model
        if (!empty($category)) {
            // Get category
            $category = Pi::api('category', 'shop')->getCategory(urldecode($category), 'slug');
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
            // Set page title
            $pageTitle = sprintf(__('List of products on %s category'), $category['title']);
        }

        // Get tag list
        if (!empty($tag)) {
            $productIDTag = array();
            // Check favourite
            if (!Pi::service('module')->isActive('tag')) {
                return $result;
            }
            // Get id from tag module
            $tagList = Pi::service('tag')->getList(urldecode($tag), $module);
            foreach ($tagList as $tagSingle) {
                $productIDTag[] = $tagSingle['item'];
            }
            // Set header and title
            $pageTitle = sprintf(__('All products from %s'), $tag);
        }

        // Get favourite list
        if (!empty($favourite) && $favourite == 1) {
            // Check favourite
            if (!Pi::service('module')->isActive('favourite')) {
                return $result;
            }
            // Get uid
            $uid = Pi::user()->getId();
            // Check user
            if (!$uid) {
                return $result;
            }
            // Get id from favourite module
            $productIDFavourite = Pi::api('favourite', 'favourite')->userFavourite($uid, $module);
            // Set page title
            $pageTitle = ('All favourite products by you');
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
            $columns = array('id');
            $select = $this->getModel('product')->select()->columns($columns)->where(function ($where) use ($titles, $recommended) {
                $whereMain = clone $where;
                $whereKey = clone $where;
                $whereMain->equalTo('status', 1);
                if (!empty($recommended) && $recommended == 1) {
                    $whereMain->equalTo('recommended', 1);
                }
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

        $columns = array('product' => new Expression('DISTINCT product'));
        $offset = (int)($page - 1) * $config['view_perpage'];
        $limit = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);

        // Set category on where link
        if (isset($categoryIDList) && !empty($categoryIDList)) {
            $whereLink['category'] = $categoryIDList;
        }

        // Set product on where link from title and attribute
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

        // Set favourite products on where link
        if (!empty($favourite) && $favourite == 1 && isset($productIDFavourite)) {
            if (isset($whereLink['product']) && !empty($whereLink['product'])) {
                $whereLink['product'] = array_intersect($productIDFavourite, $whereLink['product']);
            } elseif (!isset($whereLink['product']) || empty($whereLink['product'])) {
                $whereLink['product'] = $productIDFavourite;
            } else {
                $hasSearchResult = false;
            }
        }

        // Set tag products on where link
        if (!empty($tag) && isset($productIDTag)) {
            if (isset($whereLink['product']) && !empty($whereLink['product'])) {
                $whereLink['product'] = array_intersect($productIDTag, $whereLink['product']);
            } elseif (!isset($whereLink['product']) || empty($whereLink['product'])) {
                $whereLink['product'] = $productIDTag;
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
            'products' => $product,
            'filterList' => $filterList,
            'paginator' => array(
                'count' => $count,
                'limit' => intval($config['view_perpage']),
                'page' => $page,
            ),
            'condition' => array(
                'title' => $pageTitle,
                'urlCompare' => Pi::url($this->url('', array('controller' => 'compare'))),
            ),
            'price' => array(
                'minValue' => intval($minPrice),
                'maxValue' => intval($maxPrice),
                'minSelect' => intval($minSelect),
                'maxSelect' => intval($maxSelect),
                'step' => intval(($maxPrice - $minPrice) / 25),
                'rightToLeft' => false,
            ),
        );

        return $result;
    }

    public function brandAction()
    {
        $module = $this->params('module');
        $page = $this->params('page', 1);
        $limit = $this->params('limit');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        $brand = array();
        $offset = (int)($page - 1) * $config['view_perpage'];
        $limit = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);

        $where = array('status' => 1, 'type' => 'brand');
        $order = array('display_order ASC', 'title ASC', 'id DESC');
        $select = $this->getModel('category')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('category')->selectWith($select);
        foreach ($rowset as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $brand[] = array(
                'id' => $categorySingle['id'],
                'parent' => $categorySingle['parent'],
                'title' => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl' => $categorySingle['thumbUrl'],
            );
        }

        // Get count
        $columnsCount = array('count' => new Expression('count(*)'));
        $select = $this->getModel('category')->select()->where($where)->columns($columnsCount);
        $count = $this->getModel('category')->selectWith($select)->current()->count;

        $result = array(
            'brands' => $brand,
            'paginator' => array(
                'count' => $count,
                'limit' => intval($config['view_perpage']),
                'page' => $page,
            ),
        );

        return $result;
    }

    public function categoryAction()
    {
        $module = $this->params('module');
        $page = $this->params('page', 1);
        $limit = $this->params('limit');
        $parent = $this->params('parent');
        $tree = $this->params('tree');
        $product = $this->params('product');
        $brand = $this->params('brand');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        $category = array();
        $offset = (int)($page - 1) * $config['view_perpage'];
        $limit = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);

        // Set where
        $where = array('status' => 1, 'type' => 'category');
        if (intval($parent) > 0) {
            $where['parent'] = intval($parent);
        }

        // Check $brand
        if (intval($brand) > 0) {
            $whereBrand = array('brand' => intval($brand));
            $columnsBrand = array('category' => new Expression('DISTINCT `category_main`'));
            $select = $this->getModel('product')->select()->where($whereBrand)->columns($columnsBrand);
            $rowset = $this->getModel('product')->selectWith($select)->toArray();
            foreach ($rowset as $id) {
                $categoryIDSelect[] = $id['category'];
            }
            $where['id'] = $categoryIDSelect;
        }

        // Select
        $categoryId = array();
        $order = array('parent ASC', 'id DESC');
        $select = $this->getModel('category')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('category')->selectWith($select);
        foreach ($rowset as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $categoryId[] = $row->id;
            $category[] = array(
                'id' => $categorySingle['id'],
                'parent' => $categorySingle['parent'],
                'title' => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl' => $categorySingle['thumbUrl'],
            );
        }

        if ($product == 1 && !empty($categoryId)) {
            $products = array();
            $whereProduct = array('status' => 1, 'category_main' => $categoryId);
            $orderProduct = array('title DESC', 'id DESC');
            $select = $this->getModel('product')->select()->where($whereProduct)->order($orderProduct);
            $rowset = $this->getModel('product')->selectWith($select);
            foreach ($rowset as $row) {
                $productSingle = Pi::api('product', 'shop')->canonizeProductLight($row);
                $products[] = array(
                    'id' => $productSingle['id'],
                    'category' => $productSingle['category_main'],
                    'brand' => $productSingle['brand'],
                    'title' => $productSingle['title'],
                    'mediumUrl' => $productSingle['mediumUrl'],
                    'thumbUrl' => $productSingle['thumbUrl'],
                );
            }
        } elseif ($tree == 1) {
            // Set as tree
            $category = Pi::api('category', 'shop')->makeTree($category);
        }

        // Get count
        $columnsCount = array('count' => new Expression('count(*)'));
        $select = $this->getModel('category')->select()->where($where)->columns($columnsCount);
        $count = $this->getModel('category')->selectWith($select)->current()->count;

        // Set result
        $result = array(
            'categories' => $category,
            'products' => $products,
            'paginator' => array(
                'count' => $count,
                'limit' => intval($config['view_perpage']),
                'page' => $page,
            ),
        );

        return $result;
    }

    public function productAllAction()
    {
        // Get info from url
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
                $attributes = Pi::api('attribute', 'shop')->Product($product['id'], $product['category_main']);
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