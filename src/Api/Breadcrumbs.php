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
namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractBreadcrumbs;

class Breadcrumbs extends AbstractBreadcrumbs
{
    /**
     * {@inheritDoc}
     */
    public function load()
    {
        // Get params
        $params = Pi::service('url')->getRouteMatch()->getParams();
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Check breadcrumbs
        if ($config['view_breadcrumbs']) {
        	// Set module link
        	$moduleData = Pi::registry('module')->read($this->getModule());
        	// Make tree
        	if (!empty($params['controller']) && $params['controller'] != 'index') {
                // Set index
                $result = array(
                    array(
                        'label' => $moduleData['title'],
                        'href'  => Pi::service('url')->assemble('shop', array(
                            'module' => $this->getModule(),
                        )),
                    ),
                );
                // Set
        		switch ($params['controller']) {
                    case 'category':
                        $category = Pi::api('category', 'shop')->getCategory($params['slug'], 'slug');
                        $result[] = array(
                            'label' => $category['title'],
                        );
                        break;

                    case 'checkout':
                        switch ($params['action']) {
                            case 'cart':
                                $result[] = array(
                                    'label' => __('Cart'),
                                );
                                break;

                            case 'information':
                                $result[] = array(
                                    'label' => __('Cart'),
                                    'href'  => Pi::service('url')->assemble('shop', array(
                                        'controller' => 'checkout',
                                        'action'     => 'cart',
                                    )),
                                );
                                $result[] = array(
                                    'label' => __('Set information'),
                                );
                                break;
                        }
                        break;

                    case 'product':
                        $product = Pi::api('product', 'shop')->getProductLight($params['slug'], 'slug');
                        // Check have brand
                        if ($product['brand'] > 0) {
                            $category = Pi::api('category', 'shop')->getCategory($product['brand']);
                            $result[] = array(
                                'label' => $category['title'],
                                'href'  => $category['categoryUrl'],
                            );
                        }
                        // Set product title
                        $result[] = array(
                            'label' => $product['title'],
                        );
                        break;

                    case 'search':
                        if ($params['action'] == 'result') {
                            $result[] = array(
                                'label' => __('Search'),
                                'href'  => Pi::service('url')->assemble('shop', array(
                                    'controller' => 'search',
                                    'action'     => 'index',
                                )),
                            );
                            $result[] = array(
                                'label' => __('Result'),
                            );
                        } else {
                            $result[] = array(
                                'label' => __('Search'),
                            );
                        }
                        break;

                    case 'tag':
                        if ($params['slug']) {
                            $result[] = array(
                                'label' => __('Tag list'),
                                'href'  => Pi::service('url')->assemble('shop', array(
                                    'controller' => 'tag',
                                    'action'     => 'list',
                                )),
                            );
                            $result[] = array(
                                'label' => $params['slug'],
                            );
                        } else {
                            $result[] = array(
                                'label' => __('Tag list'),
                            );
                        }
                        break; 

                    case 'user':
                        switch ($params['action']) {
                            case 'index':
                                $result[] = array(
                                    'label' => __('Dashboard'),
                                );
                                break;

                            case 'order':
                                $result[] = array(
                                    'label' => __('Dashboard'),
                                    'href'  => Pi::service('url')->assemble('shop', array(
                                        'controller' => 'user',
                                        'action'     => 'index',
                                    )),
                                );
                                $result[] = array(
                                    'label' => __('Order information'),
                                );
                                break;
                        }
                        break;
        		}
        	} else {
                $result = array(
                    array(
                        'label' => $moduleData['title'],
                    ),
                );
            }
        	return $result;
        } else {
        	return '';
        }
    }
}