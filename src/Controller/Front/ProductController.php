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

class ProductController extends IndexController
{
	public function indexAction()
    {
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category list
        $categoryList = Pi::api('shop', 'category')->categoryList();
        // Find product
        $product = $this->getModel('product')->find($slug, 'slug');
        $product = Pi::api('shop', 'product')->canonizeProduct($product, $categoryList);
        // Check product
        if (!$product || $product['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The product not found.'));
        }
        // Update Hits
        $this->getModel('product')->update(array('hits' => $product['hits'] + 1), array('id' => $product['id']));
        // Set extra
        if ($product['extra']) {
            $extra = Pi::api('shop', 'extra')->Product($product['id']);
            $this->view()->assign('extra', $extra);
        }
        // Set related
        if ($product['related']) {
            $related = Pi::api('shop', 'related')->getListAll($product['id']);
            $this->view()->assign('related', $related);
        }
        // Get list of attached files
        if ($product['attach']) {
            $attach = Pi::api('shop', 'product')->AttachList($product['id']);
            $this->view()->assign('attach', $attach);
        }
        // Set view
        $this->view()->headTitle($product['seo_title']);
        $this->view()->headDescription($product['seo_description'], 'set');
        $this->view()->headKeywords($product['seo_keywords'], 'set');
        $this->view()->setTemplate('product_item');
        $this->view()->assign('productItem', $product);
        $this->view()->assign('categories', $product['categories']);
        $this->view()->assign('tags', array('test 1', 'test 2'));
        $this->view()->assign('config', $config);
    }

    public function printAction()
    {
    	$this->view()->setTemplate('empty');
    }
}