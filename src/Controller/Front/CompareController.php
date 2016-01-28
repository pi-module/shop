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
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;

class CompareController extends ActionController
{
    public function indexAction()
    {
        $products = $this->params('product');

        $mainProduct = Pi::api('product', 'shop')->getProductLight($products[1], 'slug');
        $products = Pi::api('product', 'shop')->getCompareList($products, $mainProduct);


        // Set url
        $url = Pi::url();
        // Set header and title
        $title = __('Compare products');
        if (!empty($products)) {
            foreach ($products as $product) {
                $title = sprintf('%s - %s', $title, $product['title']);
            }
        }
        // Set seo_keywords
        $filter = new Filter\HeadKeywords;
        $filter->setOptions(array(
            'force_replace_space' => true
        ));
        $seoKeywords = $filter($title);
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