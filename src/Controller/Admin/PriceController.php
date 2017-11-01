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

namespace Module\Shop\Controller\Admin;

use Module\Shop\Api\Csv;
use Module\Shop\Form\PriceLogFilter;
use Module\Shop\Form\PriceLogForm;
use Module\Shop\Form\PriceUpdateFilter;
use Module\Shop\Form\PriceUpdateForm;
use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Paginator\Paginator;
use Zend\Db\Sql\Predicate\Expression;

class PriceController extends ActionController
{
    public function indexAction()
    {
        // Set path
        $path = Pi::path('upload/shop/csv');
        if (!Pi::service('file')->exists($path . '/index.html')) {
            Pi::service('file')->copy(
                Pi::path('upload/index.html'),
                Pi::path('upload/shop/csv/index.html')
            );
        }

        // Set filter
        $filter = function ($fileinfo) {
            if (!$fileinfo->isFile()) {
                return false;
            }
            $filename = $fileinfo->getFilename();
            if ('index.html' == $filename) {
                return false;
            }
            return $filename;
        };
        // Get file list
        $fileList = Pi::service('file')->getList($path, $filter);
        // Set template
        $this->view()->setTemplate('price-index');
        $this->view()->assign('fileList', $fileList);
    }

    public function updateAction()
    {
        $start = $this->params('start', 0);
        $count = $this->params('count');
        $complete = $this->params('complete', 0);
        $selectCategory = $this->params('selectCategory', 0);
        $selectPercent = $this->params('selectPercent', 0);
        $info = [];
        $percent = 0;

        // Set path
        $path = Pi::path('upload/shop/csv');
        if (!Pi::service('file')->exists($path . '/index.html')) {
            Pi::service('file')->copy(
                Pi::path('upload/index.html'),
                Pi::path('upload/shop/csv/index.html')
            );
        }

        // Set form
        $form = new PriceUpdateForm('price');
        if ($this->request->isPost()) {
            // Get information
            $data = $this->request->getPost();
            $form->setInputFilter(new PriceUpdateFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Get count
                $where = ['price > ?' => 0, 'category_main' => $values['category']];
                $columns = ['count' => new Expression('count(*)')];
                $select = $this->getModel('product')->select()->columns($columns)->where($where);
                $countProduct = $this->getModel('product')->selectWith($select)->current()->count;
                // Set jump
                if ($countProduct > 0) {
                    // Set redirect
                    return $this->redirect()->toRoute('', [
                        'controller'     => 'price',
                        'action'         => 'update',
                        'selectCategory' => $values['category'],
                        'selectPercent'  => $values['percent'],
                        'start'          => 0,
                        'complete'       => 0,
                        'count'          => $countProduct,
                    ]);
                } else {
                    $message = __('Your selected category not set as main category for any product, please select other category');
                    $this->jump([
                        'action' => 'update',
                    ], $message);
                }
            }
        } elseif ($selectCategory > 0 && $selectPercent > 0) {
            // Get category list
            $categoryList = Pi::registry('categoryList', 'shop')->read();
            // Get products and send
            $where = [
                'id > ?'        => $start,
                'price > ?'     => 0,
                'category_main' => $selectCategory,
            ];
            $order = ['id ASC'];
            $select = $this->getModel('product')->select()->where($where)->order($order)->limit(50);
            $rowset = $this->getModel('product')->selectWith($select);

            // Set file
            Pi::service('audit')->attach('price-update', [
                'file'   => Pi::path('upload/shop/csv/price-update.csv'),
                'format' => 'csv',
            ]);

            // Make list
            foreach ($rowset as $row) {
                $product = Pi::api('product', 'shop')->canonizeProduct($row, $categoryList);
                $product['property'] = Pi::api('property', 'shop')->getValue($product['id']);

                // Get main price
                /* if ($product['price_main'] == 0 && $product['price_discount'] > 0) {
                    $mainPrice = $product['price_discount'];
                } else {
                    $mainPrice = $product['price_main'];
                } */
                $mainPrice = $product['price_main'];
                $priceListInfo[$product['id']]['old'] = $mainPrice;

                $priceList = [];
                $priceList[] = $mainPrice;

                // Update product property price
                if (!empty($product['property'])) {
                    foreach ($product['property'] as $propertyList) {
                        foreach ($propertyList as $property) {
                            if (isset($property['price']) && $property['price'] > 0) {

                                $priceList[] = (int)$property['price'];

                                // Make new price
                                $propertyPrice = Pi::api('price', 'shop')->makeUpdatePrice((int)$property['price'], $selectPercent);

                                // Update property price
                                $this->getModel('property_value')->update(
                                    ['price' => $propertyPrice],
                                    ['id' => (int)$property['id']]
                                );

                                // Set price log form property table
                                if (isset($property['unique_key']) && !empty($property['unique_key'])) {
                                    Pi::api('price', 'shop')->addLog(
                                        $propertyPrice,
                                        (int)$product['id'],
                                        'property',
                                        $property['unique_key']
                                    );
                                }

                                // Set extra
                                $priceListInfo[$product['id']][$property['unique_key']] = $propertyPrice;
                            }
                        }
                    }
                }

                // Set min price
                $minPrice = min($priceList);

                // Make new price
                $minPrice = Pi::api('price', 'shop')->makeUpdatePrice($minPrice, $selectPercent);

                // Update product price
                $this->getModel('product')->update(
                    ['price' => (int)$minPrice,],
                    ['id' => (int)$product['id']]
                );

                // Update link price
                $this->getModel('link')->update(
                    ['price' => (int)$minPrice],
                    ['product' => (int)$product['id']]
                );

                // Save log to csv file
                Pi::service('audit')->log('price-update', [
                    $product['id'],
                    $product['title'],
                    $selectCategory,
                    $selectPercent,
                    $mainPrice,
                    $minPrice,
                ]);

                // Set price log form price table
                Pi::api('price', 'shop')->addLog($minPrice, $product['id'], 'product');

                // Set extra
                $priceListInfo[$product['id']]['min'] = $minPrice;
                $lastId = $product['id'];
                $complete++;
            }
            // Get count
            if (!$count) {
                $where = ['price > ?' => 0, 'category_main' => $selectCategory];
                $columns = ['count' => new Expression('count(*)')];
                $select = $this->getModel('product')->select()->columns($columns)->where($where);
                $count = $this->getModel('product')->selectWith($select)->current()->count;
            }
            // Set complete
            $percent = (100 * $complete) / $count;
            // Set next url
            if ($complete >= $count) {
                $nextUrl = '';
            } else {
                $nextUrl = Pi::url($this->url('', [
                    'action'         => 'update',
                    'selectCategory' => $selectCategory,
                    'selectPercent'  => $selectPercent,
                    'start'          => $lastId,
                    'count'          => $count,
                    'complete'       => $complete,
                ]));
            }

            $info = [
                'selectCategory' => $selectCategory,
                'selectPercent'  => $selectPercent,
                'start'          => $lastId,
                'count'          => $count,
                'complete'       => $complete,
                'percent'        => $percent,
                'nextUrl'        => $nextUrl,
                'price'          => $priceListInfo,
            ];

            $percent = ($percent > 99 && $percent < 100) ? (intval($percent) + 1) : intval($percent);
        }
        // Set template
        $this->view()->setTemplate('price-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('selectCategory', $selectCategory);
        $this->view()->assign('selectPercent', $selectPercent);
        $this->view()->assign('info', $info);
        $this->view()->assign('nextUrl', $nextUrl);
        $this->view()->assign('percent', $percent);
    }

    public function syncAction()
    {
        // Get info
        $start = $this->params('start', 0);
        $count = $this->params('count');
        $complete = $this->params('complete', 0);
        $confirm = $this->params('confirm', 0);
        $priceListInfo = [];

        // Set path
        $path = Pi::path('upload/shop/csv');
        if (!Pi::service('file')->exists($path . '/index.html')) {
            Pi::service('file')->copy(
                Pi::path('upload/index.html'),
                Pi::path('upload/shop/csv/index.html')
            );
        }

        // Check confirm
        if ($confirm == 1) {
            // Get category list
            $categoryList = Pi::registry('categoryList', 'shop')->read();
            // Get products and send
            $where = [
                'id > ?' => $start,
            ];
            $order = ['id ASC'];
            $select = $this->getModel('product')->select()->where($where)->order($order)->limit(50);
            $rowset = $this->getModel('product')->selectWith($select);

            // Set file
            Pi::service('audit')->attach('price-sync', [
                'file'   => Pi::path('upload/shop/csv/price-sync.csv'),
                'format' => 'csv',
            ]);

            // Make list
            foreach ($rowset as $row) {
                $product = Pi::api('product', 'shop')->canonizeProduct($row, $categoryList);
                $product['property'] = Pi::api('property', 'shop')->getValue($product['id']);

                // Get main price
                /* if ($product['price_main'] == 0 && $product['price_discount'] > 0) {
                    $mainPrice = $product['price_discount'];
                } else {
                    $mainPrice = $product['price_main'];
                } */
                $mainPrice = $product['price_main'];

                $priceList = [];
                $priceList[] = $mainPrice;

                // Check product property
                if (!empty($product['property'])) {
                    foreach ($product['property'] as $propertyList) {
                        foreach ($propertyList as $property) {
                            if (isset($property['price']) && $property['price'] > 0) {
                                $priceList[] = (int)$property['price'];
                            }
                        }
                    }
                }

                // Set min price
                $minPrice = min($priceList);

                // Update product
                $this->getModel('product')->update(
                    ['price' => (int)$minPrice],
                    ['id' => (int)$product['id']]
                );

                // Update link
                $this->getModel('link')->update(
                    ['price' => (int)$minPrice],
                    ['product' => (int)$product['id']]
                );

                // Save log to csv file
                Pi::service('audit')->log('price-sync', [
                    $product['id'],
                    $product['title'],
                    $mainPrice,
                    $minPrice,
                ]);

                // Set price log
                Pi::api('price', 'shop')->addLog($minPrice, $product['id'], 'product');

                // Set extra
                $priceListInfo[$product['id']] = $minPrice;
                $lastId = $product['id'];
                $complete++;
            }
            // Get count
            if (!$count) {
                $columns = ['count' => new Expression('count(*)')];
                $select = $this->getModel('product')->select()->columns($columns);
                $count = $this->getModel('product')->selectWith($select)->current()->count;
            }
            // Set complete
            $percent = (100 * $complete) / $count;
            // Set next url
            if ($complete >= $count) {
                $nextUrl = '';
            } else {
                $nextUrl = Pi::url($this->url('', [
                    'action'   => 'sync',
                    'start'    => $lastId,
                    'count'    => $count,
                    'complete' => $complete,
                    'confirm'  => $confirm,
                ]));
            }

            $info = [
                'start'    => $lastId,
                'count'    => $count,
                'complete' => $complete,
                'percent'  => $percent,
                'nextUrl'  => $nextUrl,
                'price'    => $priceListInfo,
            ];

            $percent = ($percent > 99 && $percent < 100) ? (intval($percent) + 1) : intval($percent);
        } else {
            $info = [];
            $percent = 0;
            $nextUrl = Pi::url($this->url('', [
                'action'  => 'sync',
                'confirm' => 1,
            ]));
        }
        // Set template
        $this->view()->setTemplate('price-sync');
        $this->view()->assign('nextUrl', $nextUrl);
        $this->view()->assign('percent', $percent);
        $this->view()->assign('info', $info);
        $this->view()->assign('confirm', $confirm);
    }

    public function logAction()
    {
        $product = $this->params('product');
        $page = $this->params('page', 1);

        // Set form
        $form = new PriceLogForm('price');
        if ($this->request->isPost()) {
            // Get information
            $data = $this->request->getPost();
            $form->setInputFilter(new PriceLogFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set redirect
                return $this->redirect()->toRoute('', [
                    'controller' => 'price',
                    'action'     => 'log',
                    'product'    => $values['product'],
                ]);
            }
        } else {
            $data = [];
            $data['product'] = $product;
            $form->setData($data);
        }

        // Set product list
        $productList = [];

        // Get log
        $list = [];
        $where = [];
        $order = ['time_update DESC', 'id DESC'];
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        if ($product > 0) {
            $where['product'] = $product;
        }

        // Select
        $select = $this->getModel('price')->select()->where($where)->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('price')->selectWith($select);
        foreach ($rowset as $row) {
            if (!isset($productList[$row->product])) {
                $productList[$row->product] = Pi::api('product', 'shop')->getProductLight($row->product);
            }

            $list[$row->id] = $row->toArray();
            $list[$row->id]['time_update_view'] = _date($row->time_update);
            $list[$row->id]['price_view'] = Pi::api('api', 'shop')->viewPrice($row->price);
            $list[$row->id]['productId'] = $productList[$row->product]['id'];
            $list[$row->id]['productTitle'] = $productList[$row->product]['title'];
            $list[$row->id]['productStatus'] = $productList[$row->product]['status'];
            $list[$row->id]['productUrl'] = $productList[$row->product]['productUrl'];
            $list[$row->id]['productEditUrl'] = $this->url('', [
                'controller' => 'product',
                'action'     => 'update',
                'id'         => $productList[$row->product]['id'],
            ]);

            switch ($row->type) {
                case 'product':
                    $list[$row->id]['type_view'] = __('Product price');
                    break;

                case 'property':
                    $list[$row->id]['type_view'] = __('Property price');
                    break;

                case 'sale':
                    $list[$row->id]['type_view'] = __('Sale price');
                    break;
            }
        }

        // Set paginator
        $count = ['count' => new Expression('count(*)')];
        $select = $this->getModel('price')->select()->columns($count)->where($where);
        $count = $this->getModel('price')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions([
            'router' => $this->getEvent()->getRouter(),
            'route'  => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params' => array_filter([
                'module'     => $this->getModule(),
                'controller' => 'price',
                'action'     => 'log',
                'product'    => $product,
            ]),
        ]);

        // Set template
        $this->view()->setTemplate('price-log');
        $this->view()->assign('form', $form);
        $this->view()->assign('list', $list);
        $this->view()->assign('paginator', $paginator);
    }

    public function csvAction()
    {
        $addPrice = $this->params('addPrice');
        $file = Pi::path('upload/shop/csv/price.csv');
        $working = false;

        // Set path
        $path = Pi::path('upload/shop/csv');
        if (!Pi::service('file')->exists($path . '/index.html')) {
            Pi::service('file')->copy(
                Pi::path('upload/index.html'),
                Pi::path('upload/shop/csv/index.html')
            );
        }

        if (Pi::service('file')->exists($file)) {
            if ($addPrice == 'OK') {
                // Get list from csv file
                $importer = new Csv($file, true, ',', 1001);
                $prices = $importer->get(1001);

                // Set cont array
                $countOfPriceCsv = [
                    'total'     => 0,
                    'update'    => 0,
                    'notUpdate' => 0,
                ];

                // Make
                foreach ($prices as $priceSingle) {
                    $countOfPriceCsv['total']++;

                    if (isset($priceSingle['price']) && !empty($priceSingle['price']) && is_numeric($priceSingle['price'])) {
                        if (isset($priceSingle['id']) && !empty($priceSingle['id'])) {
                            $product = Pi::api('product', 'shop')->getProductLight($priceSingle['id']);
                        } elseif (isset($priceSingle['code']) && !empty($priceSingle['code'])) {
                            $product = Pi::api('product', 'shop')->getProductLight($priceSingle['code'], 'code');
                        }
                        if ($product) {
                            // Update product
                            $this->getModel('product')->update(
                                ['price' => (int)$priceSingle['price']],
                                ['id' => (int)$product['id']]
                            );

                            // Update link
                            $this->getModel('link')->update(
                                ['price' => (int)$priceSingle['price']],
                                ['product' => (int)$product['id']]
                            );

                            // Save log to csv file
                            Pi::service('audit')->attach('price-csv', [
                                'file'   => Pi::path('upload/shop/csv/price-csv-update.csv'),
                                'format' => 'csv',
                            ]);
                            Pi::service('audit')->log('price-csv', $priceSingle);

                            // Set price log
                            Pi::api('price', 'shop')->addLog($priceSingle['price'], $product['id'], 'product');

                            $countOfPriceCsv['update']++;
                        } else {
                            $countOfPriceCsv['notUpdate']++;

                            // Save log to csv file
                            Pi::service('audit')->attach('price-csv', [
                                'file'   => Pi::path('upload/shop/csv/price-csv-not-update.csv'),
                                'format' => 'csv',
                            ]);
                            Pi::service('audit')->log('price-csv', $priceSingle);
                        }
                    } else {
                        $countOfPriceCsv['notUpdate']++;

                        // Save log to csv file
                        Pi::service('audit')->attach('price-csv', [
                            'file'   => Pi::path('upload/shop/csv/price-csv-not-update.csv'),
                            'format' => 'csv',
                        ]);
                        Pi::service('audit')->log('price-csv', $priceSingle);
                    }
                }
                $message = sprintf(__('Working to import information from %s'), $file);
                $working = true;
            } else {
                $message = sprintf(__('You can import this information from %s'), $file);
            }
        } else {
            $message = sprintf(__('price.csv not exist on %s'), $file);
        }

        // Translate
        __('total');
        __('update');
        __('notUpdate');

        // Set template
        $this->view()->setTemplate('price-csv');
        $this->view()->assign('message', $message);
        $this->view()->assign('file', $file);
        $this->view()->assign('working', $working);
        $this->view()->assign('countOfPriceCsv', $countOfPriceCsv);


    }
}