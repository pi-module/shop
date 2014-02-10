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

class TagController extends IndexController
{
	public function termAction()
    {
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        $action = $this->params('action');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check slug
        if (!isset($slug) || empty($slug)) {
            $url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('The tag not set.'), 'error');
        }
        // Get id from tag module
        $tagId = array();
        $tags = Pi::service('tag')->getList($slug, $module);
        foreach ($tags as $tag) {
            $tagId[] = $tag['item'];
        }
        // Check slug
        if (empty($tagId)) {
        	$url = array('', 'module' => $module, 'controller' => 'index', 'action' => 'index');
            $this->jump($url, __('The tag not found.'), 'error');
        }
        // Set info
        $where = array('status' => 1, 'product' => $tagId);
        // Get product List
        $productList = $this->productList($where);
        // Set paginator info
        $template = array(
            'controller' => 'tag',
            'action' => 'term',
            );
        // Get paginator
        $paginator = $this->productPaginator($template, $where);
        // Set view
        $this->view()->setTemplate('product_list');
        $this->view()->assign('productList', $productList);
        $this->view()->assign('productTitle', sprintf(__('All products by %s tag'), $slug));
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
    }

    public function listAction()
    {
        // Get info from url
        $module = $this->params('module');
    	$where = array('module' => $module);
        $order = array('count DESC', 'id DESC');
    	$select = Pi::model('stats', 'tag')->select()->where($where)->order($order);
    	$rowset = Pi::model('stats', 'tag')->selectWith($select);
    	foreach ($rowset as $row) {
            $tag = Pi::model('tag', 'tag')->find($row->tag);
            $tagList[$row->id] = $row->toArray();
            $tagList[$row->id]['term'] = $tag['term'];
            $tagList[$row->id]['url'] = $this->url('', array(
            	'controller' => 'tag', 
            	'action' => 'term', 
            	'slug' => urldecode($tag['term'])
            	));
        }
        // Set view
        $this->view()->setTemplate('tag_list');
        $this->view()->assign('tagList', $tagList);
    }
}