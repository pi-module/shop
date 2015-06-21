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

class JsonController extends IndexController
{
    public function indexAction()
    {
        // Set return
        $return = array(
            'website' => Pi::url(),
            'module' => $this->params('module'),
        );
        // Set view
        $this->view()->setTemplate(false)->setLayout('layout-content');
        return Json::encode($return);
    }

    public function productAllAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set story info
        $where = array('status' => 1);
        // Get story List
        $productList = $this->productJsonList($where);
        // Set view
        $this->view()->setTemplate(false)->setLayout('layout-content');
        return Json::encode($productList);
    }

    public function productCategoryAction()
    {
        // Get info from url
        $categoryMain = $this->params('id');
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category information from model by category_main id
        $categoryMain = $this->getModel('category')->find($categoryMain);
        $categoryMain = Pi::api('category', 'shop')->canonizeCategory($categoryMain);
        // Check category
        if (!$categoryMain || $categoryMain['status'] != 1) {
            $productList = array();
        } else {
            // Set story info
            $where = array('status' => 1, 'category' => $categoryMain['id']);
            // Get story List
            $productList = $this->productJsonList($where);
        }
        // Set view
        $this->view()->setTemplate(false)->setLayout('layout-content');
        return Json::encode($productList);
    }

    public function productSingleAction()
    {
        // Get info from url
        $id = $this->params('id');
        $module = $this->params('module');
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
        $this->view()->setTemplate(false)->setLayout('layout-content');
        return Json::encode($productSingle);
    }

    public function questionAction()
    {
        // Set view
        $this->view()->setTemplate(false)->setLayout('layout-content');
        // Check post
        if ($this->request->isPost()) {
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
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'question' => $data['question'],
                    'id'       => $data['id'],
                    'title'    => $data['title'],
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

            echo '<pre>';
            print_r($data);
            echo '</pre>';
        }
    }
}