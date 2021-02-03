<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Controller\Front;

use Module\Shop\Form\CustomerManageFilter;
use Module\Shop\Form\CustomerManageForm;
use Module\Shop\Form\CustomerAdditionalFilter;
use Module\Shop\Form\CustomerAdditionalForm;
use Pi;
use Pi\Mvc\Controller\ActionController;
use Pi\Filter;
use Laminas\Math\Rand;

class DashboardController extends ActionController
{
    public function indexAction()
    {
        $authentication = Pi::api('authentication', 'company')->check();
        if (!$authentication['result']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate($authentication['error']['message'], '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check dashboard is active
        if (!$config['dashboard_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('Dashboard in inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Set header and title
        $title = __('Manage products');

        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(
            [
                'force_replace_space' => true,
            ]
        );
        $seoKeywords = $filter($title);

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'dashboard-index');
        }

        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('dashboard-index');
        $this->view()->assign('authentication', $authentication);
        $this->view()->assign('config', $config);
        $this->view()->assign('title', $title);
    }

    public function listAction()
    {
        $authentication = Pi::api('authentication', 'company')->check();
        if (!$authentication['result']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate($authentication['error']['message'], '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check dashboard is active
        if (!$config['dashboard_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('Dashboard in inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Set params
        $params = [
            'company_id' => $authentication['data']['company_id'],
        ];

        // Get product list
        $productList = Pi::api('company', 'shop')->getProductList($params);

        // Set header and title
        $title = __('List of products');

        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(
            [
                'force_replace_space' => true,
            ]
        );
        $seoKeywords = $filter($title);

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'dashboard-list');
        }

        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('dashboard-list');
        $this->view()->assign('authentication', $authentication);
        $this->view()->assign('config', $config);
        $this->view()->assign('title', $title);
        $this->view()->assign('productList', $productList);
    }

    public function manageAction()
    {
        $authentication = Pi::api('authentication', 'company')->check();
        if (!$authentication['result']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate($authentication['error']['message'], '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get info from url
        $module = $this->params('module');
        $slug   = $this->params('slug');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check dashboard is active
        if (!$config['dashboard_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('Dashboard in inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get product
        $productSingle = Pi::api('product', 'shop')->getProduct($slug, 'slug');

        // Check company
        if ($productSingle['company_id'] != $authentication['data']['company_id']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('This is not your product'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get config
        $option = [
            'brand_system' => $config['brand_system'],
        ];

        // Set form
        $form = new CustomerManageForm('product', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new CustomerManageFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Category
                $values['category'] = json_encode([$values['category_main']]);

                // Set time_update
                $values['time_update'] = time();

                // Set status
                $values['status'] = 2;

                // Save values
                $row = $this->getModel('product')->find($productSingle['id']);
                $row->assign($values);
                $row->save();

                // Jump
                $message = __('Product data saved successfully.');
                $this->jump(['action' => 'attribute', 'slug' => $row->slug], $message);
            }
        } else {
            $form->setData($productSingle);
        }

        // Set header and title
        $title = __('Manage product');

        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(
            [
                'force_replace_space' => true,
            ]
        );
        $seoKeywords = $filter($title);

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'dashboard-manage');
        }

        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('dashboard-manage');
        $this->view()->assign('authentication', $authentication);
        $this->view()->assign('config', $config);
        $this->view()->assign('title', $title);
        $this->view()->assign('productSingle', $productSingle);
    }

    public function addAction()
    {
        $authentication = Pi::api('authentication', 'company')->check();
        if (!$authentication['result']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate($authentication['error']['message'], '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check dashboard is active
        if (!$config['dashboard_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('Dashboard in inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get config
        $option = [
            'brand_system' => $config['brand_system'],
        ];

        // Set form
        $form = new CustomerManageForm('shop', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new CustomerManageFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Category
                $values['category'] = json_encode([$values['category_main']]);

                // Set time_update
                $values['time_create'] = time();
                $values['time_update'] = time();

                // Set status
                $values['status'] = 2;

                // Set slug
                $values['slug'] = Rand::getString(128, 'abcdefghijklmnopqrstuvwxyz123456789', true);

                // Set company id
                $values['company_id'] = $authentication['data']['company_id'];

                // Save values
                $row = $this->getModel('shop')->createRow();
                $row->assign($values);
                $row->save();

                // Jump
                $message = __('Product data saved successfully.');
                $this->jump(['action' => 'attribute', 'slug' => $row->slug], $message);
            }
        }

        // Set header and title
        $title = __('Add new product');

        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(
            [
                'force_replace_space' => true,
            ]
        );
        $seoKeywords = $filter($title);

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'dashboard-manage');
        }

        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('dashboard-add');
        $this->view()->assign('authentication', $authentication);
        $this->view()->assign('config', $config);
        $this->view()->assign('title', $title);
        $this->view()->assign('form', $form);
    }

    public function attributeAction()
    {
        $authentication = Pi::api('authentication', 'company')->check();
        if (!$authentication['result']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate($authentication['error']['message'], '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get info from url
        $module = $this->params('module');
        $slug   = $this->params('slug');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check dashboard is active
        if (!$config['dashboard_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('Dashboard in inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get product
        $productSingle = Pi::api('product', 'shop')->getProduct($slug, 'slug');

        // Check company
        if ($productSingle['company_id'] != $authentication['data']['company_id']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('This is not your product'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get config
        $option = [];

        // Set form
        $form = new CustomerAdditionalForm('shop', $option);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new CustomerAdditionalFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();


                // Jump
                $message = __('Attribute data saved successfully.');
                $this->jump(['action' => 'list'], $message);
            }
        }

        // Set header and title
        $title = __('Attribute information');

        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(
            [
                'force_replace_space' => true,
            ]
        );
        $seoKeywords = $filter($title);

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'dashboard-manage');
        }

        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('dashboard-attribute');
        $this->view()->assign('authentication', $authentication);
        $this->view()->assign('config', $config);
        $this->view()->assign('title', $title);
        $this->view()->assign('form', $form);
    }

    public function saleAction()
    {
        $authentication = Pi::api('authentication', 'company')->check();
        if (!$authentication['result']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate($authentication['error']['message'], '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Get info from url
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Check dashboard is active
        if (!$config['dashboard_active']) {
            $this->getResponse()->setStatusCode(403);
            $this->terminate(__('Dashboard in inactive'), '', 'error-denied');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Set params
        $params = [
            'company_id' => $authentication['data']['company_id'],
        ];

        // Get product list
        $productIdList = Pi::api('company', 'shop')->getProductIdList($params);

        // Set params
        $params = [
            'productList' => $productIdList,
        ];

        // Get order list
        $orderList = Pi::api('company', 'shop')->getOrders($params);

        // Set header and title
        $title = __('List of sales and order');

        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(
            [
                'force_replace_space' => true,
            ]
        );
        $seoKeywords = $filter($title);

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'dashboard-attribute');
        }

        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('dashboard-sale');
        $this->view()->assign('authentication', $authentication);
        $this->view()->assign('config', $config);
        $this->view()->assign('title', $title);
        $this->view()->assign('orderList', $orderList);
    }
}