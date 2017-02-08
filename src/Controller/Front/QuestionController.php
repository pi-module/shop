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

class QuestionController extends IndexController
{
    public function indexAction()
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
                        'text_ask' => $question['text_ask'],
                        'product' => $question['product'],
                        'title' => $product['title'],
                    );
                    // Set toAdmin
                    $toAdmin = array(
                        Pi::config('adminmail') => Pi::config('adminname'),
                    );
                    // Send mail to admin
                    Pi::service('notification')->send(
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