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
use Module\Shop\Form\SitemapForm;
use Zend\Db\Sql\Predicate\Expression;

class ToolsController extends ActionController
{
    public function indexAction()
    {
        // Set template
        $this->view()->setTemplate('tools-index');
    }

    public function sitemapAction()
    {
        $form = new SitemapForm('sitemap');
        $message = __('Rebuild thie module links on sitemap module tabels');
        if ($this->request->isPost()) {
            // Set form date
            $values = $this->request->getPost()->toArray();
            switch ($values['type']) {
                case '1':
                    Pi::api('product', 'shop')->sitemap();
                    Pi::api('category', 'shop')->sitemap();
                    break;

                case '2':
                    Pi::api('product', 'shop')->sitemap();
                    break;

                case '3':
                    Pi::api('category', 'shop')->sitemap();
                    break;
            }
            $message = __('Sitemap rebuild finished');
        }
        // Set view
        $this->view()->setTemplate('tools-sitemap');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Rebuild sitemap links'));
        $this->view()->assign('message', $message);
    }

    public function priceAction()
    {
        // Get info
        $start = $this->params('start', 0);
        $count = $this->params('count');
        $complete = $this->params('start', 0);
        $priceListInfo = array();
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

            if ($product['price_main'] == 0 && $product['price_discount'] > 0) {
                $mainPrice = $product['price_discount'];
            } else {
                $mainPrice = $product['price_main'];
            }
            $priceList = array();
            $priceList[] = $mainPrice;

            // Check product property
            if (!empty($product['property'])) {
                foreach ($product['property'] as $property) {
                    if ($property['price'] > 0) {
                        $priceList[] = (int)$property['price'];
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
        $percent = (100 * $start) / $count;
        // Set next url
        if ($complete >= $count) {
            $nextUrl = '';
        } else {
            $nextUrl = $this->url('', array(
                'action' => 'price',
                'start' => $lastId,
                'count' => $count,
                'complete' => $complete,
            ));
        }
        // Set template
        $this->view()->setTemplate('tools-price');
        $this->view()->assign('nextUrl', $nextUrl);
        $this->view()->assign('percent', $percent);
        $this->view()->assign('info', array(
            'start' => $lastId,
            'count' => $count,
            'complete' => $complete,
            'percent' => $percent,
            'nextUrl' => $nextUrl,
            'price' => $priceListInfo,
        ));
    }
}