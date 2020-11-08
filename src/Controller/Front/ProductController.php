<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Controller\Front;

use Module\Shop\Form\QuestionForm;
use Pi;
use Pi\Mvc\Controller\ActionController;

class ProductController extends IndexController
{
    public function indexAction()
    {
        // Get info from url
        $slug   = $this->params('slug');
        $module = $this->params('module');

        // Get config
        $config = Pi::service('registry')->config->read($module);

        // Find product
        $productSingle = Pi::api('product', 'shop')->getProduct($slug, 'slug');

        // Check product
        if (!$productSingle || $productSingle['status'] != 1) {
            $this->getResponse()->setStatusCode(404);
            $this->terminate(__('The product not found.'), '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }

        // Update Hits
        $this->getModel('product')->increment('hits', ['id' => $productSingle['id']]);

        // Get user information
        $uid = Pi::user()->getId();
        if ($uid > 0) {
            $user = Pi::api('customer', 'shop')->getCustomer($uid);
        }

        // Get attribute
        if ($productSingle['attribute'] && $config['view_attribute']) {
            $attribute = Pi::api('attribute', 'shop')->Product($productSingle['id'], $productSingle['category_main']);
            $this->view()->assign('attribute', $attribute);
        }

        // Get related
        if ($productSingle['related'] && $config['view_related']) {
            $productRelated = Pi::api('related', 'shop')->getListAll($productSingle['id']);
            $this->view()->assign('productRelated', $productRelated);
        }

        // Get attached files
        /* if ($productSingle['attach'] && $config['view_attach']) {
            $attach = Pi::api('product', 'shop')->AttachList($productSingle['id']);
            $this->view()->assign('attach', $attach);
        } */

        // Get new products in category
        if ($config['view_incategory']) {
            $where           = ['status' => 1, 'category' => $productSingle['category_main']];
            $productCategory = $this->productList($where, $config['view_incategory_count']);
            $this->view()->assign('productCategory', $productCategory);
        }

        // Set tag
        if ($config['view_tag']) {
            $tag = Pi::service('tag')->get($module, $productSingle['id'], '');
            $this->view()->assign('tag', $tag);
        }

        // Set vote
        if ($config['vote_bar'] && Pi::service('module')->isActive('vote')) {
            $vote['point']  = $productSingle['point'];
            $vote['count']  = $productSingle['count'];
            $vote['item']   = $productSingle['id'];
            $vote['table']  = 'product';
            $vote['module'] = $module;
            $vote['type']   = 'star';
            $this->view()->assign('vote', $vote);
        }

        // Set favourite
        if ($config['favourite_bar'] && Pi::service('module')->isActive('favourite')) {
            $favourite['is']     = Pi::api('favourite', 'favourite')->loadFavourite($module, 'product', $productSingle['id']);
            $favourite['item']   = $productSingle['id'];
            $favourite['table']  = 'product';
            $favourite['module'] = $module;
            $this->view()->assign('favourite', $favourite);
        }

        // Set video service
        if ($config['video_service'] && Pi::service('module')->isActive('video')) {
            $videoServiceList = Pi::api('service', 'video')->getVideo($module, 'product', $productSingle['id']);
            $this->view()->assign('videoServiceList', $videoServiceList);
        }

        // Set question
        if ($config['view_question']) {
            $question            = [];
            $question['product'] = $productSingle['id'];
            if ($uid > 0) {
                $question['uid']   = $user['id'];
                $question['email'] = $user['email'];
                $question['name']  = $user['display'];
            }

            // Set action url
            $url = Pi::url(
                $this->url(
                    '', [
                        'module'     => $module,
                        'controller' => 'product',
                        'action'     => 'question',
                    ]
                )
            );

            // Set form
            $form = new QuestionForm('question');
            $form->setAttribute('enctype', 'multipart/form-data');
            $form->setAttribute('action', $url);
            $form->setData($question);

            // Set view
            $this->view()->assign('questionForm', $form);
            $this->view()->assign(
                'questionMessage', __('You can any question about this product from us, we read your question and answer you as soon as possible')
            );
        }

        // Set main category info
        if ($config['view_description_product']) {
            $categorySingle = Pi::api('category', 'shop')->getCategory($productSingle['category_main']);
            $this->view()->assign('categorySingle', $categorySingle);
        }

        // Set property
        $property          = [];
        $property['list']  = Pi::api('property', 'shop')->getList();
        $property['value'] = Pi::api('property', 'shop')->getValue($productSingle['id']);
        $this->view()->assign('property', $property);

        // Update user processing
        $allowOrder = true;
        if ($config['processing_user']) {

            // Get user
            $userProcessing = Pi::api('user', 'shop')->get($uid);
            d($userProcessing);

            // Check user can make order
            if ($config['processing_disable_order'] && intval($userProcessing['order_active']) == 0) {
                $allowOrder = false;
                d(1);
            }

            // Check user order this product before
            if ($config['processing_order_limit'] && in_array($productSingle['id'], $userProcessing['products'])) {
                $allowOrder = false;
                d(2);
            }

            // Check user login
            if ($config['processing_login'] && !Pi::service('user')->hasIdentity()) {
                $allowOrder = false;
                d(3);
            }
        }

        // Check order is active
        if (!$config['order_active']) {
            $allowOrder = false;
        }

        // Set template
        switch ($config['product_template']) {
            case 'tab':
                $template = 'product-item-tab';
                break;

            default:
            case 'plain':
                $template = 'product-item';
                break;
        }

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'product', $productSingle['id']);
        }

        // Set view
        $this->view()->headTitle($productSingle['seo_title']);
        $this->view()->headDescription($productSingle['seo_description'], 'set');
        $this->view()->headKeywords($productSingle['seo_keywords'], 'set');
        $this->view()->setTemplate($template);
        $this->view()->assign('productSingle', $productSingle);
        $this->view()->assign('categoryItem', $productSingle['categories']);
        $this->view()->assign('config', $config);
        $this->view()->assign('allowOrder', $allowOrder);
    }
}
