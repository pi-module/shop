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
                $result = [
                    [
                        'label' => $moduleData['title'],
                        'href'  => Pi::url(
                            Pi::service('url')->assemble(
                                'shop', [
                                'module' => $this->getModule(),
                            ]
                            )
                        ),
                    ],
                ];
                // Set
                switch ($params['controller']) {
                    case 'category':
                        switch ($params['action']) {
                            case 'list':
                                $result[] = [
                                    'label' => __('Category list'),
                                ];
                                break;

                            case 'index':
                                $result[] = [
                                    'label' => __('Category list'),
                                    'href'  => Pi::url(
                                        Pi::service('url')->assemble(
                                            'shop', [
                                            'module'     => $this->getModule(),
                                            'controller' => 'category',
                                        ]
                                        )
                                    ),
                                ];

                                $category = Pi::api('category', 'shop')->getCategory($params['slug'], 'slug');
                                $result   = $this->makeCategoryList($category['parent'], $result);
                                $result[] = [
                                    'label' => $category['title'],
                                ];
                                break;
                        }
                        break;

                    case 'cart':
                        switch ($params['action']) {
                            case 'index':
                                $result[] = [
                                    'label' => __('Cart'),
                                ];
                                break;

                            case 'finish':
                                $result[] = [
                                    'label' => __('Finish'),
                                ];
                                break;
                        }
                        break;

                    case 'product':
                        $product = Pi::api('product', 'shop')->getProductLight($params['slug'], 'slug');
                        // Category list
                        $result[] = [
                            'label' => __('Category list'),
                            'href'  => Pi::url(
                                Pi::service('url')->assemble(
                                    'shop', [
                                    'module'     => $this->getModule(),
                                    'controller' => 'category',
                                ]
                                )
                            ),
                        ];
                        // Check have category_main
                        if ($product['category_main'] > 0) {
                            $category = Pi::api('category', 'shop')->getCategory($product['category_main']);
                            $result   = $this->makeCategoryList($category['parent'], $result);
                            $result[] = [
                                'label' => $category['title'],
                                'href'  => $category['categoryUrl'],
                            ];
                        }
                        // Set product title
                        $result[] = [
                            'label' => $product['title'],
                        ];
                        break;

                    case 'tag':
                        if (!empty($params['slug'])) {
                            $result[] = [
                                'label' => __('Tag list'),
                                'href'  => Pi::url(
                                    Pi::service('url')->assemble(
                                        'shop', [
                                        'controller' => 'tag',
                                        'action'     => 'index',
                                    ]
                                    )
                                ),
                            ];
                            $result[] = [
                                'label' => $params['slug'],
                            ];
                        } else {
                            $result[] = [
                                'label' => __('Tag list'),
                            ];
                        }
                        break;

                    case 'compare':
                        $result[] = [
                            'label' => __('Compare products'),
                        ];
                        break;

                    case 'favourite':
                        $result[] = [
                            'label' => __('All favourite products by you'),
                        ];
                        break;

                    case 'result':
                        $result[] = [
                            'label' => __('Search result'),
                        ];
                        break;
                }
            } else {
                $result = [
                    [
                        'label' => $moduleData['title'],
                    ],
                ];
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
            $result   = $this->makeCategoryList($category['parent'], $result);
            $result[] = [
                'label' => $category['title'],
                'href'  => $category['categoryUrl'],
            ];

        }
        return $result;
    }
}