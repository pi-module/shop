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

class CategoryController extends IndexController
{
	public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        $action = $this->params('action');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category information from model
        $category = $this->getModel('category')->find($slug, 'slug');
        // Check category
        if (!$category || $category['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The category not found.'));
        }
        // Set info
        $where = array('status' => 1, 'category' => $category['id']);
        // Get product List
        $product = $this->productList($where);
        // Set paginator info
        $template = array(
            'controller' => 'category',
            'action' => $action,
            );
        // Get paginator
        $paginator = $this->productPaginator($template, $where);
        // category list
        $category = Pi::api('shop', 'category')->categoryList($category['id']);
        // Set view
        $this->view()->setTemplate('product_list');
        $this->view()->assign('products', $product);
        $this->view()->assign('category', $category);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
    }

    public static function getMethodFromAction($action)
    {
        return 'indexAction';
    }
}