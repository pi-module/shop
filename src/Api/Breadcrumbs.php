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
                        'href' => Pi::url(Pi::service('url')->assemble('shop', array(
                            'module' => $this->getModule(),
                        ))),
                    ),
                );
                // Set
                switch ($params['controller']) {
                    case 'category':
                        switch ($params['action']) {
                            case 'list':
                                $result[] = array(
                                    'label' => __('Category list'),
                                );
                                break;

                            case 'index':
                                $result[] = array(
                                    'label' => __('Category list'),
                                    'href' => Pi::url(Pi::service('url')->assemble('shop', array(
                                        'module' => $this->getModule(),
                                        'controller' => 'category',
                                        'action' => 'list',
                                    ))),
                                );

                                $category = Pi::api('category', 'shop')->getCategory($params['slug'], 'slug');
                                $result = $this->makeCategoryList($category['parent'], $result);
                                $result[] = array(
                                    'label' => $category['title'],
                                );
                                break;
                        }
                        break;

                    case 'cart':
                        switch ($params['action']) {
                            case 'index':
                                $result[] = array(
                                    'label' => __('Cart'),
                                );
                                break;

                            case 'finish':
                                $result[] = array(
                                    'label' => __('Finish'),
                                );
                                break;
                        }
                        break;

                    case 'product':
                        $product = Pi::api('product', 'shop')->getProductLight($params['slug'], 'slug');
                        // Check have category_main
                        if ($product['category_main'] > 0) {
                            $category = Pi::api('category', 'shop')->getCategory($product['category_main']);
                            $result = $this->makeCategoryList($category['parent'], $result);
                            $result[] = array(
                                'label' => $category['title'],
                                'href' => $category['categoryUrl'],
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
                                'href' => Pi::url(Pi::service('url')->assemble('shop', array(
                                    'controller' => 'search',
                                    'action' => 'index',
                                ))),
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
                        if (!empty($params['slug'])) {
                            $result[] = array(
                                'label' => __('Tag list'),
                                'href' => Pi::url(Pi::service('url')->assemble('shop', array(
                                    'controller' => 'tag',
                                    'action' => 'list',
                                ))),
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

    public function makeCategoryList($parent, $result)
    {
        if ($parent > 0) {
            $category = Pi::api('category', 'shop')->getCategory($parent);
            $result = $this->makeCategoryList($category['parent'], $result);
            $result[] = array(
                'label' => $category['title'],
                'href' => $category['categoryUrl'],
            );

        }
        return $result;
    }
}