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
use Laminas\Db\Sql\Predicate\Expression;

class JsonController extends IndexController
{
    public function searchAction()
    {
        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Get info from url
        $params = [
            'page'          => $this->params('page', 1),
            'title'         => $this->params('title'),
            'code'          => $this->params('code'),
            'category'      => $this->params('category'),
            'categoryTitle' => $this->params('categoryTitle'),
            'tag'           => $this->params('tag'),
            'favourite'     => $this->params('favourite'),
            'recommended'   => $this->params('recommended'),
            'limit'         => $this->params('limit'),
            'order'         => $this->params('order'),
            'related'       => $this->params('related'),
            'product'       => $this->params('product'),
        ];

        // Get product list
        $productList = Pi::api('api', 'shop')->productList($params);

        // Set column class
        switch ($config['view_column']) {
            case 1:
                $productList['condition']['columnSize'] = 'col-lg-12 col-md-12 col-12';
                break;

            case 2:
                $productList['condition']['columnSize'] = 'col-lg-6 col-md-6 col-12';
                break;

            case 3:
                $productList['condition']['columnSize'] = 'col-lg-4 col-md-4 col-12';
                break;

            case 4:
                $productList['condition']['columnSize'] = 'col-lg-3 col-md-3 col-12';
                break;

            case 6:
                $productList['condition']['columnSize'] = 'col-lg-2 col-md-2 col-12';
                break;

            default:
                $productList['condition']['columnSize'] = 'col-lg-3 col-md-3 col-12';
                break;
        }


        return $productList;
    }

    /* public function brandAction()
    {
        $module = $this->params('module');
        $page   = $this->params('page', 1);
        $limit  = $this->params('limit');
        $form   = $this->params('form');
        //$uid = $this->params('uid');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        $brand  = [];
        $limit  = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);
        $offset = (int)($page - 1) * $limit;

        $where = ['status' => 1, 'type' => 'brand'];
        $order = ['display_order ASC', 'title ASC', 'id DESC'];

        if ($form) {
            $where['id'] = Pi::api('form', 'forms')->getAllowIdList($form);
        }

        $select = $this->getModel('category')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowSet = $this->getModel('category')->selectWith($select);
        foreach ($rowSet as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $count          = Pi::api('product', 'shop')->getBrandCount($categorySingle['id']);
            $hasNew         = Pi::api('product', 'shop')->getBrandHasNew($categorySingle['id']);
            $brand[]        = [
                'id'        => $categorySingle['id'],
                'parent'    => $categorySingle['parent'],
                'title'     => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl'  => $categorySingle['thumbUrl'],
                'count'     => _number($count),
                'is_new'    => $hasNew,
            ];
        }

        // Get count
        $columnsCount = ['count' => new Expression('count(*)')];
        $select       = $this->getModel('category')->select()->where($where)->columns($columnsCount);
        $count        = $this->getModel('category')->selectWith($select)->current()->count;

        $result = [
            'brands'    => $brand,
            'paginator' => [
                'count' => $count,
                'limit' => $limit,
                'page'  => $page,
            ],
            'form'      => $form,
        ];

        return $result;
    }

    public function categoryAction()
    {
        $module  = $this->params('module');
        $page    = $this->params('page', 1);
        $limit   = $this->params('limit');
        $parent  = $this->params('parent');
        $tree    = $this->params('tree');
        $product = $this->params('product');
        $brand   = $this->params('brand');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        $category = [];
        $products = [];
        $limit    = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);
        $offset   = (int)($page - 1) * $limit;

        // Set where
        $where = ['status' => 1, 'type' => 'category'];
        if (intval($parent) > 0) {
            $where['parent'] = intval($parent);
        }

        // Check $brand
        if (intval($brand) > 0) {
            $whereBrand   = ['brand' => intval($brand)];
            $columnsBrand = ['category' => new Expression('DISTINCT `category_main`')];
            $select       = $this->getModel('product')->select()->where($whereBrand)->columns($columnsBrand);
            $rowSet       = $this->getModel('product')->selectWith($select)->toArray();
            foreach ($rowSet as $id) {
                $categoryIDSelect[] = $id['category'];
            }
            $where['id'] = $categoryIDSelect;
        }

        // Select
        $categoryId = [];
        $order      = ['parent ASC', 'id DESC'];
        $select     = $this->getModel('category')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowSet     = $this->getModel('category')->selectWith($select);
        foreach ($rowSet as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $categoryId[]   = $row->id;
            $category[]     = [
                'id'        => $categorySingle['id'],
                'parent'    => $categorySingle['parent'],
                'title'     => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl'  => $categorySingle['thumbUrl'],
            ];
        }

        if ($product == 1 && !empty($categoryId)) {
            $products     = [];
            $whereProduct = ['status' => 1, 'category_main' => $categoryId];
            $orderProduct = ['title DESC', 'id DESC'];
            $select       = $this->getModel('product')->select()->where($whereProduct)->order($orderProduct);
            $rowSet       = $this->getModel('product')->selectWith($select);
            foreach ($rowSet as $row) {
                $productSingle = Pi::api('product', 'shop')->canonizeProductLight($row);
                $products[]    = [
                    'id'        => $productSingle['id'],
                    'category'  => $productSingle['category_main'],
                    'brand'     => $productSingle['brand'],
                    'title'     => $productSingle['title'],
                    'mediumUrl' => $productSingle['mediumUrl'],
                    'thumbUrl'  => $productSingle['thumbUrl'],
                    'is_new'    => $productSingle['is_new'],
                ];
            }
        } elseif ($tree == 1) {
            // Set as tree
            $category = Pi::api('category', 'shop')->makeTree($category);
        }

        // Get count
        $columnsCount = ['count' => new Expression('count(*)')];
        $select       = $this->getModel('category')->select()->where($where)->columns($columnsCount);
        $count        = $this->getModel('category')->selectWith($select)->current()->count;

        // Set result
        $result = [
            'categories' => $category,
            'products'   => $products,
            'paginator'  => [
                'count' => $count,
                'limit' => $limit,
                'page'  => $page,
            ],
        ];

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
        $where = [
            'status'          => 1,
            'time_update > ?' => $update,
        ];
        // Get story List
        $productList = $this->productJsonList($where);
        // Set view
        return $productList;
    }

    public function productCategoryAction()
    {
        // Get info from url
        $categoryMain = $this->params('id');
        $update       = $this->params('update', 0);
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
            $productList = [];
        } else {
            // Set story info
            $where = [
                'status'          => 1,
                'category'        => $categoryMain['id'],
                'time_update > ?' => $update,
            ];
            // Get story List
            $productList = $this->productJsonList($where);
        }
        // Set view
        return $productList;
    }

    public function productSingleAction()
    {
        // Get info from url
        $id     = $this->params('id');
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
            $productSingle = [];
        } else {
            $productSingle = $product;
            if ($product['attribute'] && $config['view_attribute']) {
                $attributes = Pi::api('attribute', 'shop')->Product($product['id'], $product['category_main']);
                foreach ($attributes['all'] as $attributesAll) {
                    foreach ($attributesAll['info'] as $attribute) {
                        if (!empty($attribute['name'])) {
                            $productSingle['attribute-' . $attribute['name']]            = $attribute['data'];
                            $productSingle['attribute-' . $attribute['name'] . '-title'] = $attribute['title'];
                        }
                    }
                }
            }
            // Get attach file
            //$attach = Pi::api('product', 'shop')->AttachList($product['id']);

            // Set output array
            //$i = 1;
            // generate images
            //foreach ($attach['image'] as $image) {
            //    $i++;
            //    $productSingle['extra-image-' . $i] = $image['largeUrl'];
            //}
        }

        // Check brand
        if ($product['brand'] > 0) {
            $brand                       = Pi::api('category', 'shop')->getCategory($product['brand']);
            $productSingle['brandId']    = $brand['id'];
            $productSingle['brandTitle'] = $brand['title'];
            $productSingle['brandImage'] = $brand['thumbUrl'];
        }

        $productSingle = [$productSingle];
        // Set view
        return $productSingle;
    }

    public function categorySingleAction()
    {
        // Get info from url
        $id = $this->params('id');
        // Check password
        if (!$this->checkPassword()) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('Password not set or wrong'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get category
        $category = Pi::api('category', 'shop')->getCategory($id);

        $categorySingle = [
            'id'          => $category['id'],
            'title'       => $category['title'],
            'largeUrl'    => $category['largeUrl'],
            'mediumUrl'   => $category['mediumUrl'],
            'thumbUrl'    => $category['thumbUrl'],
            'description' => $category['text_summery'] . $category['text_description'],
        ];

        $categorySingle = [$categorySingle];
        // Set view
        return $categorySingle;
    }

    public function checkPassword()
    {
        // Get info from url
        $module   = $this->params('module');
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
    } */

    /* public function visitAction()
    {
        $brand = array();
        $where = array('status' => 1, 'type' => 'brand');
        $order = array('display_order ASC', 'title ASC', 'id DESC');
        $select = $this->getModel('category')->select()->where($where)->order($order)->limit(15);
        $rowSet = $this->getModel('category')->selectWith($select);
        foreach ($rowSet as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $brand[] = array(
                'id' => $categorySingle['id'],
                'parent' => $categorySingle['parent'],
                'title' => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl' => $categorySingle['thumbUrl'],
            );
        }

        $days = array();
        $daysTitle = array(
            'Saturday' => 'شنبه',
            'Sunday' => 'یکشنبه',
            'Monday' => 'دوشنبه',
            'Tuesday' => 'سه شنبه',
            'Wednesday' => 'چهارشنبه',
            'Thursday' => 'پنجشنبه',
            'Friday' => 'جمعه',
        );

        $id = 1;
        $timestamp = strtotime('today');
        for ($i = 0; $i < 7; $i++) {
            $day = strftime('%A', $timestamp);
            $days[] = array(
                'id' => $id++,
                'name' => $day,
                'title' => $daysTitle[$day],
            );
            $timestamp = strtotime('+1 day', $timestamp);
        }

        // Set result
        $result = array(
            'day' => $days,
            'brands' => $brand,
        );

        return $result;
    } */
}
