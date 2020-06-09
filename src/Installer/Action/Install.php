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

namespace Module\Shop\Installer\Action;

use Pi;
use Pi\Application\Installer\Action\Install as BasicInstall;
use Laminas\EventManager\Event;

class Install extends BasicInstall
{
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('install.pre', [$this, 'preInstall'], 1000);
        $events->attach('install.post', [$this, 'postInstall'], 1);
        parent::attachDefaultListeners();
        return $this;
    }

    public function preInstall(Event $e)
    {
        $result = [
            'status'  => true,
            'message' => sprintf('Called from %s', __METHOD__),
        ];
        $e->setParam('result', $result);
    }

    public function postInstall(Event $e)
    {
        $module = $e->getParam('module');

        // Set model
        $productModel  = Pi::model('product', $module);
        $categoryModel = Pi::model('category', $module);
        $linkModel     = Pi::model('link', $module);

        // Add category
        $categoryData = [
            'title'            => __('Default'),
            'slug'             => __('default'),
            'text_description' => __('This is a default category for shop module'),
            'seo_title'        => __('default category'),
            'seo_keywords'     => __('default,category'),
            'seo_description'  => __('default category'),
            'time_create'      => time(),
            'time_update'      => time(),
            'status'           => '1',
        ];
        $categoryModel->insert($categoryData);

        // Add product
        $productData = [
            'title'           => __('Demo product'),
            'slug'            => __('demo-product'),
            'category'        => json_encode(['1']),
            'category_main'   => 1,
            'text_summary'    => __('This is a summery for this demo product'),
            'seo_title'       => __('demo product'),
            'seo_keywords'    => __('demo,product'),
            'seo_description' => __('demo product'),
            'status'          => '1',
            'time_create'     => time(),
            'time_update'     => time(),
            'stock'           => 1,
            'price'           => 1000,
        ];
        $productModel->insert($productData);

        // Add link
        $linkData = [
            'product'     => '1',
            'category'    => '1',
            'time_create' => time(),
            'time_update' => time(),
            'stock'       => 1,
            'price'       => 1000,
            'status'      => '1',
        ];
        $linkModel->insert($linkData);

        // Result
        $result = [
            'status'  => true,
            'message' => __('Default information added.'),
        ];
        $this->setResult('post-install', $result);
    }
}