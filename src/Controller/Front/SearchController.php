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
    	// Get property
        $option['property'] = Pi::api('shop', 'property')->Get();
        // Get extra field
        $fields = Pi::api('shop', 'extra')->Get();
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
                $this->jump($url, $message);
            }	

    	} else {
    		unset($_SESSION['shop']['search']);
    	}
    	// Set view
        $this->view()->setTemplate('search_form');
        $this->view()->assign('form', $form);
    }

    public function resultAction()
    {
        $search = $_SESSION['shop']['search'];

        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Set product info from search
        if (isset($search['title']) && !empty($search['title'])) {
        	$where = array('status' => 1, 'title LIKE ?' => '%' . $search['title'] . '%');
        } else {
        	$where = array('status' => 1);
        }
        // Set property_1
        if (isset($search['property_1']) && !empty($search['property_1'])) {
        	$where['property_1'] = $search['property_1'];
        }
        // Set property_2
        if (isset($search['property_2']) && !empty($search['property_2'])) {
        	$where['property_2'] = $search['property_2'];
        }
        // Set property_3
        if (isset($search['property_3']) && !empty($search['property_3'])) {
        	$where['property_3'] = $search['property_3'];
        }
        // Set property_4
        if (isset($search['property_4']) && !empty($search['property_4'])) {
        	$where['property_4'] = $search['property_4'];
        }
        // Set property_5
        if (isset($search['property_5']) && !empty($search['property_5'])) {
        	$where['property_5'] = $search['property_5'];
        }
        // Set property_6
        if (isset($search['property_6']) && !empty($search['property_6'])) {
        	$where['property_6'] = $search['property_6'];
        }
        // Set property_7
        if (isset($search['property_7']) && !empty($search['property_7'])) {
        	$where['property_7'] = $search['property_7'];
        }
        // Set property_8
        if (isset($search['property_8']) && !empty($search['property_8'])) {
        	$where['property_8'] = $search['property_8'];
        }
        // Set property_9
        if (isset($search['property_9']) && !empty($search['property_9'])) {
        	$where['property_9'] = $search['property_9'];
        }
        // Set property_10
        if (isset($search['property_10']) && !empty($search['property_10'])) {
        	$where['property_10'] = $search['property_10'];
        }



        $this->view()->setTemplate('empty');
        $this->view()->assign('test', $_SESSION['shop']['search']);
    }	
}