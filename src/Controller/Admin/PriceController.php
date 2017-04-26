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

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Shop\Form\PriceUpdateForm;
use Module\Shop\Form\PriceUpdateFilter;
use Zend\Db\Sql\Predicate\Expression;

class PriceController extends ActionController
{
    public function indexAction()
    {
        // Set template
        $this->view()->setTemplate('price-index');
    }

    public function updateAction()
    {
        $start = $this->params('start', 0);
        $count = $this->params('count');
        $complete = $this->params('complete', 0);
        $selectCategory = $this->params('selectCategory', 0);
        $selectPercent = $this->params('selectPercent', 0);
        $info = array();
        $percent = 0;
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
                $where = array('price > ?' => 0, 'category_main' => $values['category']);
                $columns = array('count' => new Expression('count(*)'));
                $select = $this->getModel('product')->select()->columns($columns)->where($where);
                $countProduct = $this->getModel('product')->selectWith($select)->current()->count;
                // Set jump
                if ($countProduct > 0) {
                    // Set redirect
                    return $this->redirect()->toRoute('', array(
                        'controller' => 'price',
                        'action' => 'update',
                        'selectCategory' => $values['category'],
                        'selectPercent' => $values['percent'],
                        'start' => 0,
                        'complete' => 0,
                        'count' => $countProduct,
                    ));
                } else {
                    $message = __('Your selected category not set as main category for any product, please select other category');
                    $this->jump(array(
                        'action' => 'update',
                    ), $message);
                }
            }
        } elseif ($selectCategory > 0 && $selectPercent > 0) {
            // Get category list
            $categoryList = Pi::registry('categoryList', 'shop')->read();
            // Get products and send
            $where = array(
                'id > ?' => $start,
                'price > ?' => 0,
                'category_main' => $selectCategory,
            );
            $order = array('id ASC');
            $select = $this->getModel('product')->select()->where($where)->order($order)->limit(50);
            $rowset = $this->getModel('product')->selectWith($select);

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

                $priceList = array();
                $priceList[] = $mainPrice;

                // Update product property price
                if (!empty($product['property'])) {
                    foreach ($product['property'] as $propertyList) {
                        foreach ($propertyList as $property) {
                            if (isset($property['price']) && $property['price'] > 0) {
                                echo '<pre>';
                                print_r($property);
                                echo '</pre>';
                                $priceList[] = (int)$property['price'];

                                // Make new price
                                $propertyPrice = Pi::api('price', 'shop')->makeUpdatePrice((int)$property['price'], $selectPercent);

                                // Update property price
                                $this->getModel('property_value')->update(
                                    array('price' => $propertyPrice),
                                    array('id' => (int)$property['id'])
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
                    array('price' => (int)$minPrice,),
                    array('id' => (int)$product['id'])
                );

                // Update link price
                $this->getModel('link')->update(
                    array('price' => (int)$minPrice),
                    array('product' => (int)$product['id'])
                );

                // Save log to csv file
                $args = array($product['id'], $product['title'], $selectCategory, $selectPercent, $mainPrice, $minPrice);
                Pi::service('audit')->attach('audit', array(
                    'file'  => Pi::path('log') . '/shop-price-update.csv'
                ));
                Pi::service('audit')->log('audit', $args);

                // Set price log form price table
                Pi::api('price', 'shop')->addLog($minPrice, $product['id'], 'product');

                // Set extra
                $priceListInfo[$product['id']]['min'] = $minPrice;
                $lastId = $product['id'];
                $complete++;
            }
            // Get count
            if (!$count) {
                $where = array('price > ?' => 0, 'category_main' => $selectCategory);
                $columns = array('count' => new Expression('count(*)'));
                $select = $this->getModel('product')->select()->columns($columns)->where($where);
                $count = $this->getModel('product')->selectWith($select)->current()->count;
            }
            // Set complete
            $percent = (100 * $complete) / $count;
            // Set next url
            if ($complete >= $count) {
                $nextUrl = '';
            } else {
                $nextUrl = Pi::url($this->url('', array(
                    'action' => 'update',
                    'selectCategory' => $selectCategory,
                    'selectPercent' => $selectPercent,
                    'start' => $lastId,
                    'count' => $count,
                    'complete' => $complete,
                )));
            }

            $info = array(
                'selectCategory' => $selectCategory,
                'selectPercent' => $selectPercent,
                'start' => $lastId,
                'count' => $count,
                'complete' => $complete,
                'percent' => $percent,
                'nextUrl' => $nextUrl,
                'price' => $priceListInfo,
            );

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
        $priceListInfo = array();

        // Check confirm
        if ($confirm == 1) {
            // Get category list
            $categoryList = Pi::registry('categoryList', 'shop')->read();
            // Get products and send
            $where = array(
                'id > ?' => $start,
            );
            $order = array('id ASC');
            $select = $this->getModel('product')->select()->where($where)->order($order)->limit(50);
            $rowset = $this->getModel('product')->selectWith($select);

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

                $priceList = array();
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
                    array('price' => (int)$minPrice,),
                    array('id' => (int)$product['id'])
                );

                // Update link
                $this->getModel('link')->update(
                    array('price' => (int)$minPrice),
                    array('product' => (int)$product['id'])
                );

                // Save log to csv file
                $args = array($product['id'], $product['title'], $mainPrice, $minPrice);
                Pi::service('audit')->attach('audit', array(
                    'file'  => Pi::path('log') . '/shop-price-sync.csv'
                ));
                Pi::service('audit')->log('audit', $args);

                // Set price log
                Pi::api('price', 'shop')->addLog($minPrice, $product['id'], 'product');

                // Set extra
                $priceListInfo[$product['id']] = $minPrice;
                $lastId = $product['id'];
                $complete++;
            }
            // Get count
            if (!$count) {
                $columns = array('count' => new Expression('count(*)'));
                $select = $this->getModel('product')->select()->columns($columns);
                $count = $this->getModel('product')->selectWith($select)->current()->count;
            }
            // Set complete
            $percent = (100 * $complete) / $count;
            // Set next url
            if ($complete >= $count) {
                $nextUrl = '';
            } else {
                $nextUrl = Pi::url($this->url('', array(
                    'action' => 'sync',
                    'start' => $lastId,
                    'count' => $count,
                    'complete' => $complete,
                    'confirm' => $confirm,
                )));
            }

            $info = array(
                'start' => $lastId,
                'count' => $count,
                'complete' => $complete,
                'percent' => $percent,
                'nextUrl' => $nextUrl,
                'price' => $priceListInfo,
            );

            $percent = ($percent > 99 && $percent < 100) ? (intval($percent) + 1) : intval($percent);
        } else {
            $info = array();
            $percent = 0;
            $nextUrl = Pi::url($this->url('', array(
                'action' => 'sync',
                'confirm' => 1,
            )));
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
        // Set template
        $this->view()->setTemplate('price-log');
    }
}