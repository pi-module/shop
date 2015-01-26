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
        $brand = $this->params('id');
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category information from model by brand id
        $brand = $this->getModel('category')->find($brand);
        $brand = Pi::api('category', 'shop')->canonizeCategory($brand);
        // Check category
        if (!$brand || $brand['status'] != 1) {
            $productList = array();
        } else {
            // Set story info
            $where = array('status' => 1, 'category' => $brand['id']);
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
}