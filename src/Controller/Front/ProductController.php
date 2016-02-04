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
use Module\Shop\Form\QuestionForm;
use Module\Shop\Form\QuestionFilter;
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
        // Find product
        $product = $this->getModel('product')->find($slug, 'slug');
        $product = Pi::api('product', 'shop')->canonizeProduct($product);
        // Check product
        if (!$product || $product['status'] != 1) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The product not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // Update Hits
        $this->getModel('product')->update(array('hits' => $product['hits'] + 1), array('id' => $product['id']));
        // Get user information
        $uid = Pi::user()->getId();
        if ($uid > 0) {
            $user = Pi::api('customer', 'shop')->getCustomer($uid);
        }
        // Get attribute
        if ($product['attribute'] && $config['view_attribute']) {
            $attribute = Pi::api('attribute', 'shop')->Product($product['id'], $product['category_main']);
            $this->view()->assign('attribute', $attribute);
        }
        // Get related
        if ($product['related'] && $config['view_related']) {
            $productRelated = Pi::api('related', 'shop')->getListAll($product['id']);
            $this->view()->assign('productRelated', $productRelated);
        }
        // Get attached files
        if ($product['attach'] && $config['view_attach']) {
            $attach = Pi::api('product', 'shop')->AttachList($product['id']);
            $this->view()->assign('attach', $attach);
        }
        // Get new products in category
        if ($config['view_incategory']) {
            $where = array('status' => 1, 'category' => $product['category']);
            $productCategory = $this->productList($where);
            $this->view()->assign('productCategory', $productCategory);
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
        // Set favourite
        if ($config['favourite_bar'] && Pi::service('module')->isActive('favourite')) {
            $favourite['is'] = Pi::api('favourite', 'favourite')->loadFavourite($module, 'product', $product['id']);
            $favourite['item'] = $product['id'];
            $favourite['table'] = 'product';
            $favourite['module'] = $module;
            $this->view()->assign('favourite', $favourite);
        }
        // Set question
        if ($config['view_question']) {
            $question = array();
            $question['product'] = $product['id'];
            if ($uid > 0) {
                $question['uid'] = $user['id'];
                $question['email'] = $user['email'];
                $question['name'] = $user['display'];
            }
            // Set action url
            $url = Pi::url($this->url('', array(
                'module' => $module,
                'controller' => 'product',
                'action' => 'question',
            )));
            // Set form
            $form = new QuestionForm('question');
            $form->setAttribute('enctype', 'multipart/form-data');
            $form->setAttribute('action', $url);
            $form->setData($question);
            // Set view
            $this->view()->assign('questionForm', $form);
            $this->view()->assign('questionMessage', __('You can any question about this product from us, we read your question and answer you as soon as possible'));
        }
        // Set property
        $property = array();
        $property['list'] = Pi::api('property', 'shop')->getList();
        $property['value'] = Pi::api('property', 'shop')->getValue($product['id']);
        $this->view()->assign('property', $property);
        // Set view
        $this->view()->headTitle($product['seo_title']);
        $this->view()->headDescription($product['seo_description'], 'set');
        $this->view()->headKeywords($product['seo_keywords'], 'set');
        $this->view()->setTemplate('product-item');
        $this->view()->assign('productItem', $product);
        $this->view()->assign('categoryItem', $product['categories']);
        $this->view()->assign('config', $config);
    }

    public function questionAction()
    {
        // Check post
        if ($this->request->isPost()) {
            // Get user and item
            $uid = Pi::user()->getId();
            $product = Pi::api('product', 'shop')->getProductLight(_post('product', 'int'));
            // Check item
            if (!$product || $product['status'] != 1) {
                $this->getResponse()->setStatusCode(404);
                $this->terminate(__('The product not found.'), '', 'error-404');
                $this->view()->setLayout('layout-simple');
                return;
            }
            // Set values
            $data = $this->request->getPost();
            $form = new QuestionForm('question');
            $form->setInputFilter(new QuestionFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set
                $values['time_ask'] = time();
                $values['status'] = 0;
                $values['product'] = $product['id'];
                $values['uid_ask'] = Pi::user()->getId();
                $values['ip'] = Pi::user()->getIp();
                // Save
                $row = $this->getModel('question')->createRow();
                $row->assign($values);
                $row->save();
                $question = $row->toArray();
                // Check notification module
                if (Pi::service('module')->isActive('notification')) {
                    // Set mail information
                    $information = array(
                        'name' => $question['name'],
                        'email' => $question['email'],
                        'question' => $question['question'],
                        'product' => $question['product'],
                        'title' => $product['title'],
                    );
                    // Set toAdmin
                    $toAdmin = array(
                        Pi::config('adminmail') => Pi::config('adminname'),
                    );
                    // Send mail to admin
                    Pi::api('mail', 'notification')->send(
                        $toAdmin,
                        'user_question',
                        $information,
                        Pi::service('module')->current()
                    );
                }
                // Jump
                $message = __('Your question was send successfully to admin.');
                $this->jump($product['productUrl'], $message);
            } else {
                // Set view
                $this->view()->setTemplate('product-question');
                $this->view()->assign('questionForm', $form);
                $this->view()->assign('questionMessage', __('You can any question about this product from us, we read your question and answer you as soon as possible'));
                $this->view()->assign('questionValid', 'notValid');
            }
        } else {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('Nothing set'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
    }
}