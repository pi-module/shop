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
        $this->getModel('product')->increment('hits', ['id' => $product['id']]);

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
            $where = ['status' => 1, 'category' => $product['category_main']];
            //if ($product['brand'] > 0) {
            //    $where['brand'] = $product['brand'];
            //}
            $productCategory = $this->productList($where, $config['view_incategory_count']);
            $this->view()->assign('productCategory', $productCategory);
        }

        // Set tag
        if ($config['view_tag']) {
            $tag = Pi::service('tag')->get($module, $product['id'], '');
            $this->view()->assign('tag', $tag);
        }

        // Set vote
        if ($config['vote_bar'] && Pi::service('module')->isActive('vote')) {
            $vote['point']  = $product['point'];
            $vote['count']  = $product['count'];
            $vote['item']   = $product['id'];
            $vote['table']  = 'product';
            $vote['module'] = $module;
            $vote['type']   = 'star';
            $this->view()->assign('vote', $vote);
        }

        // Set favourite
        if ($config['favourite_bar'] && Pi::service('module')->isActive('favourite')) {
            $favourite['is']     = Pi::api('favourite', 'favourite')->loadFavourite($module, 'product', $product['id']);
            $favourite['item']   = $product['id'];
            $favourite['table']  = 'product';
            $favourite['module'] = $module;
            $this->view()->assign('favourite', $favourite);
        }

        // Set video service
        if ($config['video_service'] && Pi::service('module')->isActive('video')) {
            $videoServiceList = Pi::api('service', 'video')->getVideo($module, 'product', $product['id']);
            $this->view()->assign('videoServiceList', $videoServiceList);
        }

        // Set question
        if ($config['view_question']) {
            $question            = [];
            $question['product'] = $product['id'];
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
            $categorySingle = Pi::api('category', 'shop')->getCategory($product['category_main']);
            $this->view()->assign('categorySingle', $categorySingle);
        }

        // Set property
        $property          = [];
        $property['list']  = Pi::api('property', 'shop')->getList();
        $property['value'] = Pi::api('property', 'shop')->getValue($product['id']);
        $this->view()->assign('property', $property);

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
            Pi::api('log', 'statistics')->save('shop', 'product', $product['id']);
        }

        // Set view
        $this->view()->headTitle($product['seo_title']);
        $this->view()->headDescription($product['seo_description'], 'set');
        $this->view()->headKeywords($product['seo_keywords'], 'set');
        $this->view()->setTemplate($template);
        $this->view()->assign('productItem', $product);
        $this->view()->assign('categoryItem', $product['categories']);
        $this->view()->assign('config', $config);
    }
}