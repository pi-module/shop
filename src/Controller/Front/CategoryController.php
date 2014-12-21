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
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category information from model
        $category = $this->getModel('category')->find($slug, 'slug');
        // Check category
        if (!$category || $category['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The category not found.'), 'error');
        }
        // Set info
        $where = array(
            'status'      => 1, 
            'category'    => $category['id']
        );
        // Get product List
        $productList = $this->productList($where);
        // Set paginator info
        $template = array(
            'controller'  => 'category',
            'slug'        => $slug,
        );
        // Get paginator
        $paginator = $this->productPaginator($template, $where);
        // category list
        $categories = Pi::api('category', 'shop')->categoryList($category['id']);
        // Get special
        if ($config['view_special']) {
            $specialList = Pi::api('special', 'shop')->getAll();
            $this->view()->assign('specialList', $specialList);
            $this->view()->assign('specialTitle', __('Special products'));
        }
        // Set title
        $title = sprintf(__('All products on %s category'), $category['title']);
        // Set view
        $this->view()->headTitle($category['seo_title']);
        $this->view()->headDescription($category['seo_description'], 'set');
        $this->view()->headKeywords($category['seo_keywords'], 'set');
        $this->view()->setTemplate('product_list');
        $this->view()->assign('productList', $productList);
        $this->view()->assign('productTitleH2', $title);
        $this->view()->assign('category', $category);
        $this->view()->assign('categories', $categories);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
    }

    public function listAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set info
        $categories = array();
        $where = array('status' => 1);
        $order = array('title DESC', 'id DESC');
        $select = $this->getModel('category')->select()->order($order);
        $rowset = $this->getModel('category')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $categories[$row->id] = Pi::api('category', 'shop')->canonizeCategory($row);
        }
        // Set header and title
        $title = __('Category list');
        $seoTitle = Pi::api('text', 'shop')->title($title);
        $seoDescription = Pi::api('text', 'shop')->description($title);
        $seoKeywords = Pi::api('text', 'shop')->keywords($title);
        // Set view
        $this->view()->headTitle($seoTitle);
        $this->view()->headDescription($seoDescription, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('category_list');
        $this->view()->assign('categories', $categories);
        $this->view()->assign('config', $config);
    }
}