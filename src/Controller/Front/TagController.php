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
use Module\Shop\Form\SearchForm;

class TagController extends IndexController
{
    public function indexAction()
    {
        // Check tag
        if (!Pi::service('module')->isActive('tag')) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('Tag module not installed.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check slug
        if (!isset($slug) || empty($slug)) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The tag not set.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // category list
        $categories = Pi::api('category', 'shop')->categoryList(0);
        // Set filter url
        $filterUrl = Pi::url($this->url('', array(
            'controller' => 'json',
            'action' => 'filterTag',
            'slug' => $slug
        )));
        // Set filter list
        $filterList = Pi::api('attribute', 'shop')->filterList();
        // Set header and title
        $title = sprintf(__('All products by %s tag'), $slug);
        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(array(
            'force_replace_space' => true
        ));
        $seoKeywords = $filter($title);
        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('product-angular');
        $this->view()->assign('config', $config);
        $this->view()->assign('categories', $categories);
        $this->view()->assign('filterUrl', $filterUrl);
        $this->view()->assign('filterList', $filterList);
        $this->view()->assign('productTitleH1', $title);
    }

    public function listAction()
    {
        // Get info from url
        $module = $this->params('module');
        $tagList = array();
        // Check tag module install or not
        if (Pi::service('module')->isActive('tag')) {
            $where = array('module' => $module);
            $order = array('count DESC', 'id DESC');
            $select = Pi::model('stats', 'tag')->select()->where($where)->order($order);
            $rowset = Pi::model('stats', 'tag')->selectWith($select);
            foreach ($rowset as $row) {
                $tag = Pi::model('tag', 'tag')->find($row->term, 'term');
                $tagList[$row->id] = $row->toArray();
                $tagList[$row->id]['term'] = $tag['term'];
                $tagList[$row->id]['url'] = Pi::url($this->url('', array(
                    'controller' => 'tag',
                    'action' => 'index',
                    'slug' => urldecode($tag['term'])
                )));
            }
        }
        // Set header and title
        $title = __('List of all used tags on shop');
        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(array(
            'force_replace_space' => true
        ));
        $seoKeywords = $filter($title);
        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('tag-list');
        $this->view()->assign('title', $title);
        $this->view()->assign('tagList', $tagList);
    }
}