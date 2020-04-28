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

namespace Module\Shop\Controller\Api;

use Pi;
use Pi\Mvc\Controller\ApiController;
use Zend\Db\Sql\Predicate\Expression;

class ProductController extends ApiController
{
    public function listAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'    => 1,
                'message' => __('Nothing selected'),
            ],
        ];

        // Get info from url
        $token  = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token);
        if ($check['status'] == 1) {

            // Save statistics
            if (Pi::service('module')->isActive('statistics')) {
                Pi::api('log', 'statistics')->save(
                    'shop', 'productList', 0, [
                        'source'  => $this->params('platform'),
                        'section' => 'api',
                    ]
                );
            }

            // Get info from url
            $module        = $this->params('module');
            $page          = $this->params('page', 1);
            $title         = $this->params('title');
            $code          = $this->params('code');
            $category      = $this->params('category');
            $categoryTitle = $this->params('categoryTitle');
            $tag           = $this->params('tag');
            $favourite     = $this->params('favourite');
            $recommended   = $this->params('recommended');
            $related       = $this->params('related');
            $product       = $this->params('product');
            $limit         = $this->params('limit');
            $order         = $this->params('order');

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

            // Clean category Title
            if (Pi::service('module')->isActive('search') && isset($categoryTitle) && !empty($categoryTitle)) {
                $categoryTitle = Pi::api('api', 'search')->parseQuery($categoryTitle);
            } elseif (isset($categoryTitle) && !empty($categoryTitle)) {
                $categoryTitle = _strip($categoryTitle);
            } else {
                $categoryTitle = '';
            }

            // Clean code
            if (isset($code) && !empty($code)) {
                $code   = _strip($code);
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
            if (!empty($recommended) && $recommended == 1) {
                $whereLink['recommended'] = 1;
            }
            if (!empty($code)) {
                $whereLink['code LIKE ?'] = '%' . $code . '%';
            }

            // Set page title
            $pageTitle = __('List of products');

            // Set order
            switch ($order) {
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
            if (!empty($related) && $related == 1 && !empty($product) && intval($product) > 0) {
                $productSingle = Pi::api('product', 'shop')->getProductLight(intval($product));
                $category      = $productSingle['category_main'];
            }

            // Get category information from model
            if (!empty($category)) {
                // Get category
                if (is_numeric($category) && intval($category) > 0) {
                    $category = Pi::api('category', 'shop')->getCategory(intval($category));
                } else {
                    $category = Pi::api('category', 'shop')->getCategory($category, 'slug');
                }
                // Check category
                if (!$category || $category['status'] != 1) {
                    return $result;
                }
                // category list
                $categories = Pi::api('category', 'shop')->categoryList($category['id']);
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
            if (!empty($tag)) {
                $productIDTag = [];
                // Check favourite
                if (!Pi::service('module')->isActive('tag')) {
                    return $result;
                }
                // Get id from tag module
                $tagList = Pi::service('tag')->getList($tag, $module);
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
                $checkTitle = true;
                $titles     = is_array($title) ? $title : [$title];
                $columns    = ['id'];
                $select     = $this->getModel('product')->select()->columns($columns)->where(
                    function ($where) use ($titles, $recommended, $code) {
                        $whereMain        = clone $where;
                        $whereTitleKey    = clone $where;
                        $whereSubTitleKey = clone $where;

                        // Set where Main
                        $whereMain->equalTo('status', 1);
                        if (!empty($recommended) && $recommended == 1) {
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
                $rowSet     = $this->getModel('product')->selectWith($select);
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
            $product = [];
            $count   = 0;

            $columns = ['product' => new Expression('DISTINCT product'), '*'];
            $limit   = (intval($limit) > 0) ? intval($limit) : intval($config['view_perpage']);
            $offset  = (int)($page - 1) * $limit;

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
            if ($config['view_price_filter']) {
                $columnsPrice = ['id', 'price'];
                $limitPrice   = 1;
                $maxPrice     = 1000;
                $minPrice     = 0;

                $orderPrice  = ['price DESC', 'id DESC'];
                $selectPrice = $this->getModel('link')->select()->where($whereLink)->columns($columnsPrice)->order($orderPrice)->limit($limitPrice);
                $rowPrice    = $this->getModel('link')->selectWith($selectPrice)->current();
                if ($rowPrice) {
                    $rowPrice = $rowPrice->toArray();
                    $maxPrice = $rowPrice['price'];
                }

                $orderPrice  = ['price ASC', 'id ASC'];
                $selectPrice = $this->getModel('link')->select()->where($whereLink)->columns($columnsPrice)->order($orderPrice)->limit($limitPrice);
                $rowPrice    = $this->getModel('link')->selectWith($selectPrice)->current();
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
                $select = $this->getModel('link')->select()->where($whereLink)->columns($columns)->order($order)->offset($offset)->limit($limit);
                $rowSet = $this->getModel('link')->selectWith($select)->toArray();
                foreach ($rowSet as $id) {
                    $productIDSelect[] = $id['product'];
                }

                // Get list of product
                if (!empty($productIDSelect)) {
                    $where  = ['status' => 1, 'id' => $productIDSelect];
                    $select = $this->getModel('product')->select()->where($where)->order($order);
                    $rowSet = $this->getModel('product')->selectWith($select);
                    foreach ($rowSet as $row) {
                        $product[] = Pi::api('product', 'shop')->canonizeProductFilter($row, $categoryList, $filterList);
                    }
                }

                // Get count
                $columnsCount = ['count' => new Expression('count(DISTINCT `product`)')];
                $select       = $this->getModel('link')->select()->where($whereLink)->columns($columnsCount);
                $count        = $this->getModel('link')->selectWith($select)->current()->count;
            }

            // Search on category
            $categoryList = [];
            if (!empty($categoryTitle)) {
                $whereCategory                 = ['status' => 1];
                $whereCategory['title LIKE ?'] = '%' . $categoryTitle . '%';
                $orderCategory                 = ['title DESC', 'id DESC'];
                $select                        = $this->getModel('category')->select()->where($whereCategory)->order($orderCategory)->offset($offset)->limit(
                    $limit
                );
                $rowSet                        = $this->getModel('category')->selectWith($select);
                foreach ($rowSet as $row) {
                    $categoryList[] = Pi::api('category', 'shop')->canonizeCategory($row);
                }
            }

            // Set result
            $result = [
                'result' => true,
                'data'   => [
                    'products'   => $product,
                    'categories' => $categoryList,
                    'filterList' => $filterList,
                    'paginator'  => [
                        'count' => $count,
                        'limit' => $limit,
                        'page'  => $page,
                    ],
                    'condition'  => [
                        'title'       => $pageTitle,
                        'urlCompare'  => Pi::url($this->url('', ['controller' => 'compare'])),
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
                ],
                'error'  => [],
            ];

        } else {
            // Set error
            $result['error'] = [
                'code'    => 2,
                'message' => $check['message'],
            ];
        }

        // Return result
        return $result;
    }

    public function singleAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'    => 1,
                'message' => __('Nothing selected'),
            ],
        ];

        // Get info from url
        $token  = $this->params('token');
        $id     = $this->params('id');

        // Check token
        $check = Pi::api('token', 'tools')->check($token);
        if ($check['status'] == 1) {

            // Save statistics
            if (Pi::service('module')->isActive('statistics')) {
                Pi::api('log', 'statistics')->save(
                    'shop', 'productSingle', $this->params('id'), [
                        'source'  => $this->params('platform'),
                        'section' => 'api',
                    ]
                );
            }

            // Check id
            if (intval($id) > 0) {
                $result['data'] = Pi::api('product', 'shop')->getProduct(intval($id));

                // Update hits
                $this->getModel('product')->increment('hits', ['id' => $result['data']['id']]);

                // Attribute
                $result['data']['attributeList'] = [];
                if ($result['data']['attribute']) {
                    $result['data']['attributeList'] = Pi::api('attribute', 'product')->Product($result['data']['id'], $result['data']['category_main']);
                }

                // Check data
                if (!empty($result['data'])) {
                    $result['result'] = true;
                } else {
                    // Set error
                    $result['error'] = [
                        'code'    => 4,
                        'message' => __('Data is empty'),
                    ];
                }
            } else {
                // Set error
                $result['error'] = [
                    'code'    => 3,
                    'message' => __('Id not selected'),
                ];
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => 2,
                'message' => $check['message'],
            ];
        }

        // Check final result
        if ($result['result']) {
            $result['error'] = [];
        }

        // Return result
        return $result;
    }
}