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

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Laminas\Db\Sql\Predicate\Expression;

/*
 * Pi::api('api', 'shop')->productList($params);
 * Pi::api('api', 'shop')->categoryList();
 * Pi::api('api', 'shop')->viewPrice($price);
 */

class Api extends AbstractApi
{
    public function productList($params)
    {
        // Get info from url
        $module = $this->getModule();

        // Set has search result
        $hasSearchResult = true;

        // Clean title
        if (Pi::service('module')->isActive('search') && isset($params['title']) && !empty($params['title'])) {
            $title = Pi::api('api', 'search')->parseQuery($params['title']);
        } elseif (isset($params['title']) && !empty($params['title'])) {
            $title = _strip($params['title']);
        } else {
            $title = '';
        }

        // Clean category Title
        if (Pi::service('module')->isActive('search') && isset($params['categoryTitle']) && !empty($params['categoryTitle'])) {
            $categoryTitle = Pi::api('api', 'search')->parseQuery($params['categoryTitle']);
        } elseif (isset($params['categoryTitle']) && !empty($params['categoryTitle'])) {
            $categoryTitle = _strip($params['categoryTitle']);
        } else {
            $categoryTitle = '';
        }

        // Clean code
        if (isset($params['code']) && !empty($params['code'])) {
            $code = _strip($params['code']);
        } else {
            $code = '';
        }

        // Clean params
        $paramsClean = [];
        foreach ($_GET as $key => $value) {
            $key               = _strip($key);
            $value             = _strip($value);
            $paramsClean[$key] = $value;
        }

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Set empty result
        $result = [
            'products'   => [],
            'categories' => [],
            'filterList' => [],
            'paginator'  => [],
            'condition'  => [],
            'price'      => [],
        ];

        // Set where link
        $whereLink = ['status' => 1];
        if (isset($params['recommended']) && $params['recommended'] == 1) {
            $whereLink['recommended'] = 1;
        }
        if (isset($code) && !empty($code)) {
            $whereLink['code LIKE ?'] = '%' . $code . '%';
        }

        // Set page title
        $pageTitle = __('List of products');

        // Set order
        switch ($params['order']) {
            case 'title':
                $order = ['title DESC', 'id DESC'];
                break;

            case 'titleASC':
                $order = ['title ASC', 'id ASC'];
                break;

            case 'hits':
                $order = ['hits DESC', 'id DESC'];
                break;

            case 'hitsASC':
                $order = ['hits ASC', 'id ASC'];
                break;

            case 'create':
                $order = ['time_create DESC', 'id DESC'];
                break;

            case 'createASC':
                $order = ['time_create ASC', 'id ASC'];
                break;

            case 'update':
                $order = ['time_update DESC', 'id DESC'];
                break;

            case 'updateASC':
                $order = ['time_update ASC', 'id ASC'];
                break;

            case 'recommended':
                $order = ['recommended DESC', 'time_create DESC', 'id DESC'];
                break;

            case 'price':
                $order = ['price DESC', 'id DESC'];
                break;

            case 'priceASC':
                $order = ['price ASC', 'id ASC'];
                break;

            case 'stock':
                $order = ['stock DESC', 'id DESC'];
                break;

            case 'stockASC':
                $order = ['stock ASC', 'id ASC'];
                break;

            case 'sold':
                $order = ['sold DESC', 'id DESC'];
                break;

            default:
                $order = ['time_create DESC', 'id DESC'];
                break;
        }

        // Get related
        if (isset($params['related']) && $params['related'] == 1 && isset($params['product']) && intval($params['product']) > 0) {
            $productSingle      = Pi::api('product', 'shop')->getProductLight(intval($params['product']));
            $params['category'] = $productSingle['category_main'];
        }

        // Get category information from model
        if (isset($params['category']) && !empty($params['category'])) {
            // Get category
            if (is_numeric($params['category']) && intval($params['category']) > 0) {
                $category = Pi::api('category', 'shop')->getCategory(intval($params['category']));
            } else {
                $category = Pi::api('category', 'shop')->getCategory($params['category'], 'slug');
            }

            // Check category
            if (!$category || $category['status'] != 1) {
                return $result;
            }

            // category list
            $categories = Pi::api('category', 'shop')->categoryListByParent($category['id']);

            // Get id list
            $categoryIDList   = [];
            $categoryIDList[] = $category['id'];
            foreach ($categories as $singleCategory) {
                $categoryIDList[] = $singleCategory['id'];
            }

            // Set page title
            $pageTitle = sprintf(__('List of products on %s category'), $category['title']);
        }

        // Get tag list
        if (isset($params['tag']) && !empty($params['tag'])) {
            $productIDTag = [];
            // Check favourite
            if (!Pi::service('module')->isActive('tag')) {
                return $result;
            }
            // Get id from tag module
            $tagList = Pi::service('tag')->getList($params['tag'], $module);
            foreach ($tagList as $tagSingle) {
                $productIDTag[] = $tagSingle['item'];
            }
            // Set header and title
            $pageTitle = sprintf(__('All products from %s'), $params['tag']);
        }

        // Get favourite list
        if (isset($params['favourite']) && !empty($params['favourite']) && $params['favourite'] == 1) {
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
        $filterList   = Pi::api('attribute', 'shop')->filterList();
        $categoryList = Pi::registry('categoryList', 'shop')->read();

        // Set product ID list
        $checkTitle     = false;
        $checkAttribute = false;
        $productIDList  = [
            'title'     => [],
            'attribute' => [],
        ];

        // Check title from product table
        if (isset($title) && !empty($title)) {
            $checkTitle  = true;
            $titles      = is_array($title) ? $title : [$title];
            $columns     = ['id'];
            $recommended = isset($params['recommended']) ? $params['recommended'] : 0;
            $select      = Pi::model('product', $this->getModule())->select()->columns($columns)->where(
                function ($where) use ($titles, $recommended, $code) {
                    $whereMain        = clone $where;
                    $whereTitleKey    = clone $where;
                    $whereSubTitleKey = clone $where;

                    // Set where Main
                    $whereMain->equalTo('status', 1);
                    if (isset($recommended) && $recommended == 1) {
                        $whereMain->equalTo('recommended', 1);
                    }
                    if (!empty($code)) {
                        $whereMain->like('code', '%' . $code . '%');
                    }

                    // Set where title
                    foreach ($titles as $term) {
                        $whereTitleKey->like('title', '%' . $term . '%')->and;
                    }

                    // Set where  subtitle
                    foreach ($titles as $term) {
                        $whereSubTitleKey->like('subtitle', '%' . $term . '%')->and;
                    }

                    $where->andPredicate($whereMain)->andPredicate($whereTitleKey)->orPredicate($whereSubTitleKey);
                }
            )->order($order);
            $rowSet      = Pi::model('product', $this->getModule())->selectWith($select);
            foreach ($rowSet as $row) {
                $productIDList['title'][$row->id] = $row->id;
            }
        }

        // Check attribute
        if (!empty($paramsClean)) {
            // Make attribute list
            $attributeList = [];
            foreach ($filterList as $filterSingle) {
                if (isset($paramsClean[$filterSingle['name']]) && !empty($paramsClean[$filterSingle['name']])) {
                    $attributeList[$filterSingle['name']] = [
                        'field' => $filterSingle['id'],
                        'data'  => $paramsClean[$filterSingle['name']],
                    ];
                }
            }
            // Search on attribute
            if (!empty($attributeList)) {
                $checkAttribute = true;
                $column         = ['product'];
                foreach ($attributeList as $attributeSingle) {
                    $where  = [
                        'field' => $attributeSingle['field'],
                        'data'  => $attributeSingle['data'],
                    ];
                    $select = $this->getModel('field_data')->select()->where($where)->columns($column);
                    $rowSet = $this->getModel('field_data')->selectWith($select);
                    foreach ($rowSet as $row) {
                        $productIDList['attribute'][$row->product] = $row->product;
                    }
                }
            }
        }

        // Set info
        $productList = [];
        $count       = 0;

        $columns = ['product' => new Expression('DISTINCT product')];
        $limit   = (isset($params['limit']) && intval($params['limit']) > 0) ? intval($params['limit']) : intval($config['view_perpage']);
        $page    = (isset($params['page']) && intval($params['page']) > 0) ? intval($params['page']) : 1;
        $offset  = (int)($page - 1) * $limit;

        // Set category on where link
        if (isset($categoryIDList) && !empty($categoryIDList)) {
            $whereLink['category'] = $categoryIDList;
        }

        // Set product on where link from title and attribute
        if ($checkTitle && $checkAttribute) {
            if (isset($productIDList['title']) && !empty($productIDList['title']) && isset($productIDList['attribute'])
                && !empty($productIDList['attribute'])
            ) {
                $whereLink['product'] = array_intersect($productIDList['title'], $productIDList['attribute']);
            } else {
                $hasSearchResult = false;
            }
        } elseif ($checkTitle) {
            if (isset($productIDList['title']) && !empty($productIDList['title'])) {
                $whereLink['product'] = $productIDList['title'];
            } else {
                $hasSearchResult = false;
            }
        } elseif ($checkAttribute) {
            if (isset($productIDList['attribute']) && !empty($productIDList['attribute'])) {
                $whereLink['product'] = $productIDList['attribute'];
            } else {
                $hasSearchResult = false;
            }
        }

        // Set favourite products on where link
        if (isset($params['favourite']) && !empty($params['favourite']) && $params['favourite'] == 1 && isset($productIDFavourite)) {
            if (isset($whereLink['product']) && !empty($whereLink['product'])) {
                $whereLink['product'] = array_intersect($productIDFavourite, $whereLink['product']);
            } elseif (!isset($whereLink['product']) || empty($whereLink['product'])) {
                $whereLink['product'] = $productIDFavourite;
            } else {
                $hasSearchResult = false;
            }
        }

        // Set tag products on where link
        if (isset($params['tag']) && !empty($params['tag']) && isset($productIDTag)) {
            if (isset($whereLink['product']) && !empty($whereLink['product'])) {
                $whereLink['product'] = array_intersect($productIDTag, $whereLink['product']);
            } elseif (!isset($whereLink['product']) || empty($whereLink['product'])) {
                $whereLink['product'] = $productIDTag;
            } else {
                $hasSearchResult = false;
            }
        }

        // Get max price
        if ($config['view_price_filter']) {
            $columnsPrice = ['id', 'price'];
            $limitPrice   = 1;
            $maxPrice     = 1000;
            $minPrice     = 0;

            $orderPrice  = ['price DESC', 'id DESC'];
            $selectPrice = Pi::model('link', $this->getModule())->select()->where($whereLink)->columns($columnsPrice)->order($orderPrice)->limit($limitPrice);
            $rowPrice    = Pi::model('link', $this->getModule())->selectWith($selectPrice)->current();
            if ($rowPrice) {
                $rowPrice = $rowPrice->toArray();
                $maxPrice = $rowPrice['price'];
            }

            $orderPrice  = ['price ASC', 'id ASC'];
            $selectPrice = Pi::model('link', $this->getModule())->select()->where($whereLink)->columns($columnsPrice)->order($orderPrice)->limit($limitPrice);
            $rowPrice    = Pi::model('link', $this->getModule())->selectWith($selectPrice)->current();
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
        } else {
            $minPrice  = 0;
            $maxPrice  = 0;
            $minSelect = 0;
            $maxSelect = 0;
        }

        // Check has Search Result
        if ($hasSearchResult) {
            // Get info from link table
            $select = Pi::model('link', $this->getModule())->select()->where($whereLink)->columns($columns)->order($order)->offset($offset)->limit($limit);
            $rowSet = Pi::model('link', $this->getModule())->selectWith($select)->toArray();
            foreach ($rowSet as $id) {
                $productIDSelect[] = $id['product'];
            }

            // Get list of product
            if (isset($productIDSelect) && !empty($productIDSelect)) {
                $where  = ['status' => 1, 'id' => $productIDSelect];
                $select = Pi::model('product', $this->getModule())->select()->where($where)->order($order);
                $rowSet = Pi::model('product', $this->getModule())->selectWith($select);
                foreach ($rowSet as $row) {
                    $productList[$row->id] = Pi::api('product', 'shop')->canonizeProductFilter($row, $categoryList, $filterList);
                }
            }

            // Get count
            $columnsCount = ['count' => new Expression('count(DISTINCT `product`)')];
            $select       = Pi::model('link', $this->getModule())->select()->where($whereLink)->columns($columnsCount);
            $count        = Pi::model('link', $this->getModule())->selectWith($select)->current()->count;
        }

        // Search on category
        /* if (isset($categoryTitle) && !empty($categoryTitle)) {
            $categoryList  = [];
            $whereCategory = [
                'status'       => 1,
                'type'         => 'category',
                'title LIKE ?' => '%' . $categoryTitle . '%',
            ];
            $orderCategory = ['title DESC', 'id DESC'];
            $select        = $this->getModel('category')->select()->where($whereCategory)->order($orderCategory);
            $rowSet        = $this->getModel('category')->selectWith($select);
            foreach ($rowSet as $row) {
                $categoryList[] = Pi::api('category', 'shop')->canonizeCategory($row);
            }
        } */

        // Set result
        $result = [
            'products'   => array_values($productList),
            'categories' => $categoryList,
            'filterList' => $filterList,
            'paginator'  => [
                'count' => $count,
                'limit' => $limit,
                'page'  => $page,
            ],
            'condition'  => [
                'title'       => $pageTitle,
                'urlCompare'  => Pi::url(Pi::service('url')->assemble('', ['controller' => 'compare'])),
                'priceFilter' => $config['view_price_filter'],
                'addToCart'   => $config['view_add_to_cart'],
            ],
            'price'      => [
                'minValue'    => intval($minPrice),
                'maxValue'    => intval($maxPrice),
                'minSelect'   => intval($minSelect),
                'maxSelect'   => intval($maxSelect),
                'step'        => intval(($maxPrice - $minPrice) / 10),
                'rightToLeft' => false,
            ],
        ];

        return $result;
    }

    public function categoryList()
    {
        $category = [];

        $where  = ['status' => 1, 'type' => 'category'];
        $order  = ['title ASC', 'id DESC'];
        $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('category', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $categorySingle = Pi::api('category', 'shop')->canonizeCategory($row);
            $category[]     = [
                'id'        => $categorySingle['id'],
                'slug'      => $categorySingle['slug'],
                'parent'    => $categorySingle['parent'],
                'title'     => $categorySingle['title'],
                'mediumUrl' => $categorySingle['mediumUrl'],
                'thumbUrl'  => $categorySingle['thumbUrl'],
            ];
        }

        $category = Pi::api('category', 'shop')->makeTree($category);

        // Get count
        $columnsCount = ['count' => new Expression('count(*)')];
        $select       = Pi::model('category', $this->getModule())->select()->where($where)->columns($columnsCount);
        $count        = Pi::model('category', $this->getModule())->selectWith($select)->current()->count;

        // Set result
        $result = [
            'categories' => $category,
            'paginator'  => [
                'count' => $count,
            ],
        ];

        return $result;
    }

    public function viewPrice($price)
    {
        if (Pi::service('module')->isActive('order')) {
            // Load language
            Pi::service('i18n')->load(['module/order', 'default']);
            // Set price
            $viewPrice = Pi::api('api', 'order')->viewPrice($price);
        } else {
            $viewPrice = _currency($price);
        }
        return $viewPrice;
    }
}
