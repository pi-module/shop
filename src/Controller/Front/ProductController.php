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
use Module\Shop\Form\ReviewForm;
use Module\Shop\Form\ReviewFilter;
use Zend\Json\Json;

class ProductController extends IndexController
{
    /**
     * review Columns
     */
    protected $reviewColumns = array(
        'id', 'uid', 'product', 'title', 'description', 'time_create', 'official', 'status'
    );

    public function indexAction()
    {
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Get category list
        $categoryList = Pi::api('shop', 'category')->categoryList();
        // Find product
        $product = $this->getModel('product')->find($slug, 'slug');
        $product = Pi::api('shop', 'product')->canonizeProduct($product, $categoryList);
        // Check product
        if (!$product || $product['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The product not found.'));
        }
        // Update Hits
        $this->getModel('product')->update(array('hits' => $product['hits'] + 1), array('id' => $product['id']));
        // Get extra
        if ($product['extra'] && $config['view_extra']) {
            $extra = Pi::api('shop', 'extra')->Product($product['id']);
            $this->view()->assign('extra', $extra);
        }
        // Get related
        if ($product['related'] && $config['view_related']) {
            $related = Pi::api('shop', 'related')->getListAll($product['id']);
            $this->view()->assign('related', $related);
        }
        // Get attached files
        if ($product['attach'] && $config['view_attach']) {
            $attach = Pi::api('shop', 'product')->AttachList($product['id']);
            $this->view()->assign('attach', $attach);
        }
        // Get new products in category
        if ($config['view_incategory']) {
            $where = array('status' => 1, 'category' => $product['category']);
            $productList = $this->productList($where);
            $this->view()->assign('productList', $productList);
            $this->view()->assign('productTitle', __('New products'));
        }
        // Get reviews
        if ($config['view_review_official'] && $config['view_review_user']) {
            $review = array();
            if ($config['view_review_official']) {
                $review['official'] = Pi::api('shop', 'review')->official($product['id']);
            }
            if ($config['view_review_user']) {
                $review['list'] = Pi::api('shop', 'review')->listReview($product['id'], 1);
            }
            $this->view()->assign('review', $review);
        }
        // Set tag
        $tag = Pi::service('tag')->get($module, $product['id'], '');
        // Set view
        $this->view()->headTitle($product['seo_title']);
        $this->view()->headDescription($product['seo_description'], 'set');
        $this->view()->headKeywords($product['seo_keywords'], 'set');
        $this->view()->setTemplate('product_item');
        $this->view()->assign('productItem', $product);
        $this->view()->assign('categories', $product['categories']);
        $this->view()->assign('config', $config);
        $this->view()->assign('tag', $tag);
    }

    public function printAction()
    {
        $this->view()->setTemplate('empty');
    }

    public function reviewAction()
    {
        // Check user is login or not
        Pi::service('authentication')->requireLogin();
        // Get info from url
        $slug = $this->params('slug');
        $module = $this->params('module');
        // Find product
        $product = $this->getModel('product')->find($slug, 'slug');
        $product = Pi::api('shop', 'product')->canonizeProduct($product, $categoryList);
        // Check product
        if (!$product || $product['status'] != 1) {
            $this->jump(array('', 'module' => $module, 'controller' => 'index'), __('The product not found.'));
        }
        // Form
        $option = array('side' => 'front');
        $form = new ReviewForm('review', $option);
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new ReviewFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set just category fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->reviewColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set values
                $values['status'] = 2;
                $values['time_create'] = time();
                $values['uid'] = Pi::user()->getId();
                $values['product'] = $product['id'];
                $values['official'] = 0;
                // Save values
                $row = $this->getModel('review')->createRow();
                $row->assign($values);
                $row->save();
                // Update review count
                Pi::api('shop', 'product')->reviewCount($product['id']);
                // Check it save or not
                if ($row->id) {
                    $message = __('Review data saved successfully. And it show after admin review');
                    $url = array('', 
                        'module' => $module, 
                        'controller' => 'product',
                        'action' => 'index', 
                        'slug' => $product['slug']
                    );
                    $this->jump($url, $message);
                } else {
                    $message = __('Review data not saved.');
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }   
        } else {
            $message = 'You can add new review';
        }
        // Set view
        $this->view()->setTemplate('product_review');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Review'));
        $this->view()->assign('message', $message);
    }
}