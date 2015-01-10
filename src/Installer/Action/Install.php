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
namespace Module\Shop\Installer\Action;

use Pi;
use Pi\Application\Installer\Action\Install as BasicInstall;
use Zend\EventManager\Event;
use Zend\Json\Json;

class Install extends BasicInstall
{
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('install.pre', array($this, 'preInstall'), 1000);
        $events->attach('install.post', array($this, 'postInstall'), 1);
        parent::attachDefaultListeners();
        return $this;
    }

    public function preInstall(Event $e)
    {
        $result = array(
            'status' => true,
            'message' => sprintf('Called from %s', __METHOD__),
        );
        $e->setParam('result', $result);
    }

    public function postInstall(Event $e)
    {
        $module = $e->getParam('module');
        
        // Set model
        $productModel = Pi::model('product', $module);
        $categoryModel = Pi::model('category', $module);
        $linkModel = Pi::model('link', $module);

        // Add category
        $categoryData = array(
            'title'            => __('Default'),
            'slug'             => __('default'),
            'text_description' => __('This is a default category for shop module'),
            'seo_title'        => __('default category'),
            'seo_keywords'     => __('default,category'),
            'seo_description'  => __('default category'),
            'time_create'      => time(),
            'time_update'      => time(),
            'status'           => '1',
        );
        $categoryModel->insert($categoryData);
        
        // Add product
        $productData = array(
            'title'            => __('Demo product'),
            'slug'             => __('demo-product'),
            'category'         => Json::encode(array('1')),
            'brand'            => 1,
            'text_summary'     => __('This is a summery for this demo product'),
            'seo_title'        => __('demo product'),
            'seo_keywords'     => __('demo,product'),
            'seo_description'  => __('demo product'),
            'status'           => '1',
            'time_create'      => time(),
            'time_update'      => time(),
            'stock'            => 1,
            'price'            => 1000,
        );
        $productModel->insert($productData);

        // Add link
        $linkData = array(
            'product'          => '1',
            'category'         => '1',
            'time_create'      => time(),
            'time_update'      => time(),
            'stock'            => 1,
            'price'            => 1000,
            'status'           => '1',
        );
        $linkModel->insert($linkData);

        // Result
        $result = array(
            'status'           => true,
            'message'          => __('Default information added.'),
        );
        $this->setResult('post-install', $result);
    }
}