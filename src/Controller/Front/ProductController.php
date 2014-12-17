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
        $categoryList = Pi::api('category', 'shop')->categoryList();
        // Find product
        $product = $this->getModel('product')->find($slug, 'slug');
        $product = Pi::api('product', 'shop')->canonizeProduct($product, $categoryList);
        // Check product
        if (!$product || $product['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The product not found.'), 'error');
        }
        // Update Hits
        $this->getModel('product')->update(array('hits' => $product['hits'] + 1), array('id' => $product['id']));
        // Get extra
        if ($product['extra'] && $config['view_extra']) {
            $extra = Pi::api('extra', 'shop')->Product($product['id']);
            $this->view()->assign('extra', $extra);
        }
        // Get related
        if ($product['related'] && $config['view_related']) {
            $related = Pi::api('related', 'shop')->getListAll($product['id']);
            $this->view()->assign('related', $related);
        }
        // Get attached files
        if ($product['attach'] && $config['view_attach']) {
            $attach = Pi::api('product', 'shop')->AttachList($product['id']);
            $this->view()->assign('attach', $attach);
        }
        // Get new products in category
        if ($config['view_incategory']) {
            $where = array('status' => 1, 'category' => $product['category']);
            $productList = $this->productList($where);
            $this->view()->assign('productList', $productList);
            $this->view()->assign('productTitle', __('New products'));
        }
        // Set tag
        if ($config['view_tag']) {
            $tag = Pi::service('tag')->get($module, $product['id'], '');
            $this->view()->assign('tag', $tag);  
        }
        // Set vote
        if ($config['vote_bar'] && Pi::service('module')->isActive('vote')) {
            $vote['point'] = $product['point'];
            $vote['count'] = $product['count'];
            $vote['item'] = $product['id'];
            $vote['table'] = 'product';
            $vote['module'] = $module;
            $vote['type'] = 'plus';
            $this->view()->assign('vote', $vote);
        }
        // favourite
        if ($config['favourite_bar'] && Pi::service('module')->isActive('favourite')) {
            $favourite['is'] = Pi::api('favourite', 'favourite')->loadFavourite($module, 'product', $product['id']);
            $favourite['item'] = $product['id'];
            $favourite['table'] = 'product';
            $favourite['module'] = $module;
            $this->view()->assign('favourite', $favourite);
        }
        // Set view
        $this->view()->headTitle($product['seo_title']);
        $this->view()->headDescription($product['seo_description'], 'set');
        $this->view()->headKeywords($product['seo_keywords'], 'set');
        $this->view()->setTemplate('product_item');
        $this->view()->assign('productItem', $product);
        $this->view()->assign('categoryItem', $product['categories']);
        $this->view()->assign('config', $config);
    }
}