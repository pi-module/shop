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

use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;

class CompareController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $slugList = $this->params('product');
        $mainProduct = [];
        $products = [];
        // Check product list
        if (!empty($slugList)) {
            $mainProduct = Pi::api('product', 'shop')->getProductLight($slugList[1], 'slug');
            $products = Pi::api('product', 'shop')->getCompareList($slugList, $mainProduct);
        }
        // Set url
        $url = Pi::url($this->url('', ['controller' => 'compare']));
        foreach ($slugList as $slug) {
            $url = sprintf('%s/%s', $url, $slug);
        }
        // Set header and title
        $title = __('Compare products');
        if (!empty($products)) {
            foreach ($products as $product) {
                $title = sprintf('%s - %s', $title, $product['title']);
            }
        }
        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions([
            'force_replace_space' => true,
        ]);
        $seoKeywords = $filter($title);

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'compare');
        }

        // Set view
        $this->view()->headTitle($title);
        $this->view()->headDescription($title, 'set');
        $this->view()->headKeywords($seoKeywords, 'set');
        $this->view()->setTemplate('product-compare');
        $this->view()->assign('products', $products);
        $this->view()->assign('mainProduct', $mainProduct);
        $this->view()->assign('title', $title);
        $this->view()->assign('url', $url);
    }
}