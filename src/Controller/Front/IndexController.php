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
use Module\Shop\Form\SearchForm;
use Module\Shop\Form\SearchFilter;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate\Expression;

class IndexController extends ActionController
{
    /* public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);

        if (isset($config['view_type']) && $config['view_type'] == 'ajax') {
            $this->ajaxView();
        } else {
            $this->normalView();
        }
    } */

    /* public function filterAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $data = $data->toArray();
            // Set search session
            $search = array();
            // Set title
            if (isset($data['title']) && !empty($data['title'])) {
                $search['title'] = $data['title'];
            }
            // Set price_from
            if (isset($data['price_from']) && intval($data['price_from']) > 0) {
                $search['price_from'] = intval($data['price_from']);
            }
            // Set price_to
            if (isset($data['price_to']) && intval($data['price_to']) > 0) {
                $search['price_to'] = intval($data['price_to']);
            }
            // Set category
            if (isset($data['category']) && intval($data['category']) > 0) {
                $search['category'] = intval($data['category']);
            }
            // Set attribute
            $attributeSearch = Pi::api('attribute', 'shop')->SearchForm($data);
            foreach ($attributeSearch as $attribute) {
                $search[$attribute['field']] = $attribute['data'];
            }
            // Make url
            $url = $this->url('shop', array(
                'controller' => 'index',
                'action' => 'index',
                'q' => '?' . http_build_query($search),
            ));
            // jump
            return $this->jump($url);
        } else {
            $message = __('Search again');
            $url = array('action' => 'index');
            $this->jump($url, $message, 'error');
        }
    } */

    /* public function normalView()
    {
        // Get info from url
        $module = $this->params('module');
        $search = $this->params('q');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set search form
        $fields = Pi::api('attribute', 'shop')->Get();
        $option['field'] = $fields['attribute'];
        $form = new SearchForm('search', $option);
        $form->setAttribute('action', Pi::url($this->url('shop', array(
            'module' => $module,
            'controller' => 'search',
            'action' => 'filter',
        ))));
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new SearchFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $data = $form->getData();
                $search = array();
                foreach ($data as $key => $value) {
                    if ($value && !empty($value)) {
                        $search[$key] = $value;
                    }
                }
                // Set log
                Pi::api('log', 'shop')->addSearchLog($search);
                // Make url
                $url = $this->url('shop', array(
                    'controller' => 'index',
                    'action' => 'index',
                    'q' => '?' . http_build_query($search),
                ));
                // jump
                return $this->jump($url);
            }
        }
        // Check set search
        if (!empty($search)) {
            // Get info from url
            $page = $this->params('page', 1);
            // Unset page
            if (isset($search['page'])) {
                unset($search['page']);
            }
            // Set product info from search
            $where = array(
                'status' => 1
            );
            // Set title
            if (isset($search['title'])
                && !empty($search['title'])
            ) {
                // check type
                if (!isset($search['type']) || empty($search['type'])) {
                    $search['type'] = 1;
                }
                // switch
                switch ($search['type']) {
                    default:
                    case 1:
                        $where['title LIKE ?'] = '%' . $search['title'] . '%';
                        break;

                    case 2:
                        $where['title LIKE ?'] = $search['title'] . '%';
                        break;

                    case 3:
                        $where['title LIKE ?'] = '%' . $search['title'];
                        break;

                    case 4:
                        $where['title LIKE ?'] = $search['title'];
                        break;
                }
            }
            // Set price_from
            if (isset($search['price_from'])
                && intval($search['price_from']) > 0
            ) {
                $where['price >= ?'] = $search['price_from'];
            }
            // Set price_to
            if (isset($search['price_to'])
                && intval($search['price_to']) > 0
            ) {
                $where['price <= ?'] = $search['price_from'];
            }
            // Set category
            if (isset($search['category'])
                && intval($search['category']) > 0
            ) {
                $categoryId = Pi::api('category', 'shop')->findFromCategory(intval($search['category']));
            }
            // Set attribute
            $attributeSearch = Pi::api('attribute', 'shop')->SearchForm($search);
            if (!empty($attributeSearch)) {
                $attributeId = Pi::api('attribute', 'shop')->findFromAttribute($attributeSearch);
            }
            // Set where id
            if (!empty($categoryId) && !empty($attributeId)) {
                $productId = array_merge($categoryId, $attributeId);
                $productId = array_unique($productId);
                $where['id'] = $productId;
            } elseif (!empty($categoryId) && empty($attributeId)) {
                $where['id'] = $categoryId;
            } elseif (empty($categoryId) && !empty($attributeId)) {
                $where['id'] = $attributeId;
            }

            // Get product List
            $productList = $this->searchList($where);
            // Set paginator info
            $template = array(
                'controller' => 'index',
                'action' => 'index',
                'q' => $search,
            );
            // Get paginator
            $paginator = $this->searchPaginator($template, $where);
            // Set header and title
            if (isset($search['title'])
                && !empty($search['title'])
            ) {
                $title = sprintf(__('Search result of %s'), $search['title']);
            } else {
                $title = __('Search result');
            }
            // Set seo_keywords
            $filter = new Filter\HeadKeywords;
            $filter->setOptions(array(
                'force_replace_space' => true
            ));
            $seoKeywords = $filter($title);
            // Set view
            $this->view()->headTitle($title);
            $this->view()->headDescription($title, 'set');
            $this->view()->headKeywords($seoKeywords, 'set');
            $this->view()->setTemplate('product-list');
            $this->view()->assign('page', $page);
            $this->view()->assign('paginator', $paginator);
            $this->view()->assign('productList', $productList);
            $this->view()->assign('productTitleH1', $title);
            $this->view()->assign('showSearchDesc', 1);
        } else {
            // Check homepage type
            if ($config['homepage_type'] == 'list') {
                // Get info from url
                $page = $this->params('page', 1);
                // Set product info
                $where = array('status' => 1);
                // Get product List
                $productList = $this->productList($where);
                // Set paginator info
                $template = array(
                    'controller' => 'index',
                    'action' => 'index',
                );
                // Get paginator
                $paginator = $this->productPaginator($template, $where);
                // Set view
                $this->view()->setTemplate('product-list');
                $this->view()->assign('page', $page);
                $this->view()->assign('paginator', $paginator);
                $this->view()->assign('productList', $productList);
                $this->view()->assign('productTitleH1', __('New products'));
                $this->view()->assign('showIndexDesc', 1);
            } elseif ($config['homepage_type'] == 'brand') {
                $title = (!empty($config['homepage_title'])) ? $config['homepage_title'] : __('Shop index');
                // Set view
                $this->view()->setTemplate('homepage');
                $this->view()->assign('productTitleH1', $title);
            }
        }
        // category list
        $category = Pi::api('category', 'shop')->categoryList(0);
        // Get special
        if ($config['view_special']) {
            $specialList = Pi::api('special', 'shop')->getAll();
            $this->view()->assign('specialList', $specialList);
        }
        // Set view
        $this->view()->assign('categories', $category);
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
        $this->view()->assign('isHomepage', 1);
    } */

    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // category list
        $categories = Pi::api('category', 'shop')->categoryList(0);
        // Get special
        if ($config['view_special']) {
            $specialList = Pi::api('special', 'shop')->getAll();
            $this->view()->assign('specialList', $specialList);
        }
        // Check homepage type
        switch ($config['homepage_type']) {
            default:
            case 'list':
                // Set filter url
                $filterUrl = Pi::url($this->url('', array(
                    'controller' => 'json',
                    'action' => 'filterIndex'
                )));
                // Set filter list
                $filterList = Pi::api('attribute', 'shop')->filterList();
                // Set view
                $this->view()->setTemplate('product-angular');
                $this->view()->assign('config', $config);
                $this->view()->assign('categories', $categories);
                $this->view()->assign('filterUrl', $filterUrl);
                $this->view()->assign('filterList', $filterList);
                $this->view()->assign('productTitleH1', __('New products'));
                $this->view()->assign('showIndexDesc', 1);
                $this->view()->assign('isHomepage', 1);
                break;

            case 'brand':
                // Set title
                $title = (!empty($config['homepage_title'])) ? $config['homepage_title'] : __('Shop index');
                // Set view
                $this->view()->setTemplate('homepage');
                $this->view()->assign('config', $config);
                $this->view()->assign('categories', $categories);
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

    /* public function searchList($where)
    {
        // Set info
        $product = array();
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
        $select = $this->getModel('product')->select()->where($where)
            ->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('product')->selectWith($select);
        foreach ($rowset as $row) {
            $product[$row->id] = Pi::api('product', 'shop')->canonizeProduct($row);
        }
        // return product
        return $product;
    } */

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

    public function productPaginator($template, $where)
    {
        $template['module'] = $this->params('module');
        $template['sort'] = $this->params('sort');
        $template['stock'] = $this->params('stock');
        $template['page'] = $this->params('page', 1);
        // get count
        $columns = array('count' => new Expression('count(DISTINCT `product`)'));
        $select = $this->getModel('link')->select()->where($where)->columns($columns);
        $template['count'] = $this->getModel('link')->selectWith($select)->current()->count;
        // paginator
        $paginator = $this->canonizePaginator($template);
        return $paginator;
    }

    /* public function searchPaginator($template, $where)
    {
        $template['module'] = $this->params('module');
        $template['sort'] = $this->params('sort');
        $template['stock'] = $this->params('stock');
        $template['page'] = $this->params('page', 1);
        // get count
        $columns = array('count' => new Expression('count(*)'));
        $select = $this->getModel('product')->select()->where($where)->columns($columns);
        $template['count'] = $this->getModel('product')->selectWith($select)->current()->count;
        // paginator
        $paginator = $this->canonizePaginator($template);
        return $paginator;
    } */

    public function canonizePaginator($template)
    {
        $template['slug'] = (isset($template['slug'])) ? $template['slug'] : '';
        $template['action'] = (isset($template['action'])) ? $template['action'] : 'index';
        // paginator
        $paginator = Paginator::factory(intval($template['count']));
        $paginator->setItemCountPerPage(intval($this->config('view_perpage')));
        $paginator->setCurrentPageNumber(intval($template['page']));
        $paginator->setUrlOptions(array(
            'router' => $this->getEvent()->getRouter(),
            'route' => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params' => array_filter(array(
                'module' => $this->getModule(),
                'controller' => $template['controller'],
                'action' => $template['action'],
                'slug' => $template['slug'],
            )),
            //'options' => $options,
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