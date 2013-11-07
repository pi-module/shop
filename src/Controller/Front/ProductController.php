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
        // Find product
        $product = $this->getModel('product')->find($slug, 'slug')->toArray();
        // Check product
        if (!$product || $product['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The product not found.'));
        }
        // Update Hits
        $this->getModel('product')->update(array('hits' => $product['hits'] + 1), array('id' => $product['id']));
        // Set date
        $product['time_create_view'] = _date($product['time_create']);
        $product['time_update_view'] = _date($product['time_update']);
        // Set text
        $product['summary'] = Pi::service('markup')->render($product['summary'], 'text', 'html');
        $product['description'] = Pi::service('markup')->render($product['description'], 'text', 'html');
        // Get category list
        $categoryList = Pi::api('shop', 'category')->categoryList();
        // Set category information
        $productCategory = Json::decode($product['category']);
        foreach ($productCategory as $category) {
            $product['categories'][$category]['title'] = $categoryList[$category]['title'];
            $product['categories'][$category]['slug'] = $categoryList[$category]['slug'];
            $product['categories'][$category]['url'] = $this->url('', array(
                'module'        => $module,
                'controller'    => 'category',
                'slug'          => $categoryList[$category]['slug'],
            ));
        }
        // Set image url
        if ($product['image']) {
            $product['originalUrl'] = Pi::url(sprintf('upload/%s/original/%s/%s', $this->config('image_path'), $product['path'], $product['image']));
            $product['largeUrl'] = Pi::url(sprintf('upload/%s/large/%s/%s', $this->config('image_path'), $product['path'], $product['image']));
            $product['mediumUrl'] = Pi::url(sprintf('upload/%s/medium/%s/%s', $this->config('image_path'), $product['path'], $product['image']));
            $product['thumbUrl'] = Pi::url(sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $product['path'], $product['image']));
        }
        // Set view
        $this->view()->headTitle($product['seo_title']);
        $this->view()->headDescription($product['seo_description'], 'set');
        $this->view()->headKeywords($product['seo_keywords'], 'set');
        $this->view()->setTemplate('product_item');
        $this->view()->assign('product', $product);
        $this->view()->assign('config', $config);
    }

    public function printAction()
    {
    	$this->view()->setTemplate('empty');
    }
}