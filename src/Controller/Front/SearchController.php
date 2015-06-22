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
use Pi\Paginator\Paginator;
use Module\Shop\Form\SearchForm;
use Module\Shop\Form\SearchFilter;
use Zend\Json\Json;

class SearchController extends IndexController
{
	public function indexAction()
    {
    	$option = array();
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set search form
        $fields = Pi::api('attribute', 'shop')->Get();
        $option['field'] = $fields['attribute'];
        $form = new SearchForm('search', $option);
        $form->setAttribute('action', Pi::url($this->url('shop', array(
            'module'        => $module,
            'controller'    => 'search',
            'action'        => 'filter',
        ))));
    	// Set view
        $this->view()->headTitle($config['text_title_search']);
        $this->view()->headDescription($config['text_description_search'], 'set');
        $this->view()->headKeywords($config['text_keywords_search'], 'set');
        $this->view()->setTemplate('search-form');
        $this->view()->assign('form', $form);
    }

    public function filterAction()
    {
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $data = $data->toArray();
            // Set search session
            $search = array();
            // Set title
            if (isset($data['title']) && !empty($data['title'])) {
                $search['title'] = $data['title'];
            }
            // Set price_from
            if (isset($data['price_from']) && intval($data['price_from']) > 0) {
                $search['price_from'] = intval($data['price_from']);
            }
            // Set price_to
            if (isset($data['price_to']) && intval($data['price_to']) > 0) {
                $search['price_to'] = intval($data['price_to']);
            }
            // Set category
            if (isset($data['category']) && intval($data['category']) > 0) {
                $search['category'] = intval($data['category']);
            }
            // Set attribute
            $attributeSearch = Pi::api('attribute', 'shop')->SearchForm($data);
            foreach ($attributeSearch as $attribute) {
                $search[$attribute['field']] = $attribute['data'];
            }
            // Make url
            $url = $this->url('', array(
                'controller' => 'search',
                'action'     => 'result',
                'slug'       => http_build_query($search),
            ));
            // jump
            return $this->jump($url);
        } else {
            $message = __('Search again');
            $url = array('action' => 'index');
            $this->jump($url, $message, 'error');
        }
    }

    public function resultAction()
    {
        // Get info from url
        $module = $this->params('module');
        $slug = $this->params('slug');
        // Get search
        parse_str($slug, $search);
        if (empty($search)) {
            $message = __('Your search session is empty, please search again');
            $url = array('action' => 'index');
            $this->jump($url, $message, 'error');
        }
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
            && intval($search['price_from']) > 0)
        {
            $where['price >= ?'] = $search['price_from'];
        }
        // Set price_to
        if (isset($search['price_to']) 
            && intval($search['price_to']) > 0)
        {
            $where['price <= ?'] = $search['price_from'];
        }
        // Set category
        if (isset($search['category']) 
            && intval($search['category']) > 0)
        {
            $categoryId = Pi::api('category', 'shop')->findFromCategory(intval($search['category']));
        }
        // Set attribute
        $attributeSearch = Pi::api('attribute', 'shop')->SearchForm($search);
        if (!empty($attributeSearch)) {
            $attributeId = Pi::api('attribute', 'shop')->findFromAttribute($attributeSearch);
        }
        // Set where id
        if (!empty($categoryId) && !empty($attributeId)) {
            $productId = array_merge($categoryId, $attributeId);
            $productId = array_unique($productId);
            $where['id'] = $productId;
        } elseif (!empty($categoryId) && empty($attributeId)) {
            $where['id'] = $categoryId;
        } elseif (empty($categoryId) && !empty($attributeId)) {
            $where['id'] = $attributeId;
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
        // Set search form
        $fields = Pi::api('attribute', 'shop')->Get();
        $option['field'] = $fields['attribute'];
        $form = new SearchForm('search', $option);
        $form->setData($search);
        $form->setAttribute('action', Pi::url($this->url('shop', array(
            'module'        => $module,
            'controller'    => 'search',
            'action'        => 'filter',
        ))));
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
        $this->view()->setTemplate('product-list');
        $this->view()->assign('productList', $productList);
        $this->view()->assign('productTitleH1', $title);
        $this->view()->assign('paginator', $paginator);
        $this->view()->assign('config', $config);
        $this->view()->assign('form', $form);
    }	
}