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
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;
use Zend\Db\Sql\Predicate\Expression;

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
        $category = Pi::api('category', 'shop')->getCategory($slug, 'slug');
        // Check category
        if (!$category || $category['status'] != 1) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The category not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Update Hits
        $this->getModel('category')->increment('hits', ['id' => $category['id']]);
        // category list
        $categoriesJson = Pi::api('category', 'shop')->categoryListJson();
        // Check display type
        switch ($category['display_type']) {
            case 'product':
                // Set template
                $template = 'product-angular';
                break;

            case 'subcategory':
                // Get info
                $list = [];
                $where = ['status' => 1, 'parent' => $category['id']];
                $order = ['display_order ASC', 'time_create DESC', 'title ASC'];
                $select = $this->getModel('category')->select()->where($where)->order($order);
                $rowset = $this->getModel('category')->selectWith($select);
                // Make list
                foreach ($rowset as $row) {
                    $list[$row->id] = Pi::api('category', 'shop')->canonizeCategory($row);
                }
                // Set view
                $this->view()->assign('list', $list);
                // Set template
                $template = 'category-single';
                break;
        }
        // Set view
        $this->view()->headTitle($category['seo_title']);
        $this->view()->headDescription($category['seo_description'], 'set');
        $this->view()->headKeywords($category['seo_keywords'], 'set');
        $this->view()->setTemplate($template);
        $this->view()->assign('config', $config);
        $this->view()->assign('category', $category);
        $this->view()->assign('categoriesJson', $categoriesJson);
        $this->view()->assign('pageType', 'category');
    }

    public function listAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set info
        $categories = [];
        $where = ['status' => 1];
        $order = ['display_order DESC', 'title ASC', 'id DESC'];
        $select = $this->getModel('category')->select()->where($where)->order($order);
        $rowset = $this->getModel('category')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $categories[$row->id] = Pi::api('category', 'shop')->canonizeCategory($row);
        }
        // Set category tree
        $categoryTree = [];
        if (!empty($categories)) {
            $categoryTree = Pi::api('category', 'shop')->makeTreeOrder($categories);
        }
        // Set header and title
        $title = __('Category list');
        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions([
            'force_replace_space' => true,
        ]);
        $seoKeywords = $filter($title);
        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('category-list');
        $this->view()->assign('categories', $categories);
        $this->view()->assign('categoryTree', $categoryTree);
        $this->view()->assign('config', $config);
    }
}