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
use Pi\Paginator\Paginator;
use Module\Shop\Form\SearchForm;
use Module\Shop\Form\SearchFilter;
use Zend\Json\Json;

class SearchController extends IndexController
{
	public function indexAction()
    {
    	$option = array();
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get extra field
        $fields = Pi::api('extra', 'shop')->Get();
        $option['field'] = $fields['extra'];
        // Set form
    	$form = new SearchForm('search', $option);
    	if ($this->request->isPost()) {
    		$data = $this->request->getPost();
    		$form->setInputFilter(new SearchFilter($fields['extra']));
            $form->setData($data);
            if ($form->isValid()) {
            	$_SESSION['shop']['search'] = $form->getData();
            	$message = __('Your search successfully. Go to result page');
            	$url = array('action' => 'result');
                $this->jump($url, $message, 'success');
            }
    	} else {
    		unset($_SESSION['shop']['search']);
    	}
    	// Set view
        $this->view()->headTitle($config['text_title_search']);
        $this->view()->headDescription($config['text_description_search'], 'set');
        $this->view()->headKeywords($config['text_keywords_search'], 'set');
        $this->view()->setTemplate('search_form');
        $this->view()->assign('form', $form);
    }

    public function resultAction()
    {
        // Get search
        $search = $_SESSION['shop']['search'];
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set product info from search
        $where = array('status' => 1);
        // Set title
        if (isset($search['title']) 
            && !empty($search['title'])
        ) {
            switch ($search['type']) {
                default:
                case 1:
                    $where['title LIKE ?'] = '%' . $search['title'] . '%';
                    break;

                case 2:
                    $where['title LIKE ?'] = $search['title'] . '%';
                    break;
                
                case 3:
                    $where['title LIKE ?'] = '%' . $search['title'];
                    break;
                
                case 4:
                    $where['title LIKE ?'] = $search['title'];
                    break;          
            }
        }
        // Set price_from
        if (isset($search['price_from']) 
            && !empty($search['price_from']))
        {
            $where['price >= ?'] = $search['price_from'];
        }
        // Set price_to
        if (isset($search['price_to']) 
            && !empty($search['price_to']))
        {
            $where['price <= ?'] = $search['price_from'];
        }
        // Set category
        if (isset($search['category']) 
            && !empty($search['category']) 
            && is_array($search['category']))
        {
            $categoryId = Pi::api('category', 'shop')->findFromCategory($search['category']);
        }
        // Set extra
        $extraSearch = Pi::api('extra', 'shop')->SearchForm($search);
        if (!empty($extraSearch)) {
            $extraId = Pi::api('extra', 'shop')->findFromExtra($extraSearch);
        }
        // Set where id
        if (!empty($categoryId) && !empty($extraId)) {
            $productId = array_merge($categoryId, $extraId);
            $productId = array_unique($productId);
            $where['id'] = $productId;
        } elseif (!empty($categoryId) && empty($extraId)) {
            $where['id'] = $categoryId;
        } elseif (empty($categoryId) && !empty($extraId)) {
            $where['id'] = $extraId;
        } 
        // Get product List
        $productList = $this->searchList($where);
        // Set paginator info
        $template = array(
            'controller' => 'search',
            'action' => 'result',
            );
        // Get paginator
        $paginator = $this->searchPaginator($template, $where);
        // Set header and title
        if (isset($search['title']) 
            && !empty($search['title']))
        {
            $title = sprintf(__('Search result of %s'), $search['title']);
        } else {
            $title = __('Search result');
        }
        $seoTitle = Pi::api('text', 'shop')->title($title);
        $seoDescription = Pi::api('text', 'shop')->description($title);
        $seoKeywords = Pi::api('text', 'shop')->keywords($title);
        // Set view
        $this->view()->headTitle($seoTitle);
        $this->view()->headDescription($seoDescription, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('product_list');
        $this->view()->assign('productList', $productList);
        $this->view()->assign('productTitleH1', $title);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
    }	
}