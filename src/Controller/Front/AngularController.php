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

class AngularController extends ActionController
{
    public function indexAction()
    {
        // Show it just to login users
        if (Pi::user()->getId() == 0) {
            $this->getResponse()->setStatusCode(401);
            $this->terminate(__('So sorry, At this moment order is inactive'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get search form
        $filterList = Pi::api('attribute', 'shop')->filterList();
        // Set view
        $this->view()->setTemplate('angular');
        $this->view()->assign('config', $config);
        $this->view()->assign('listFilter', $filterList);
    }

    public function jsonAction()
    {
        $product = array();
        // Get search form
        $filterList = Pi::api('attribute', 'shop')->filterList();
        $categoryList = Pi::registry('categoryList', 'shop')->read();
        // Set info
        $where = array('status' => 1);
        $order = array('time_create DESC', 'id DESC');
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
}