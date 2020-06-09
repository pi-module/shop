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

namespace Module\Shop\Controller\Api;

use Pi;
use Pi\Mvc\Controller\ApiController;
use Laminas\Db\Sql\Predicate\Expression;

class ProductController extends ApiController
{
    public function listAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'    => 1,
                'message' => __('Nothing selected'),
            ],
        ];

        // Get info from url
        $token  = $this->params('token');

        // Check token
        $check = Pi::api('token', 'tools')->check($token);
        if ($check['status'] == 1) {

            // Save statistics
            if (Pi::service('module')->isActive('statistics')) {
                Pi::api('log', 'statistics')->save(
                    'shop', 'productList', 0, [
                        'source'  => $this->params('platform'),
                        'section' => 'api',
                    ]
                );
            }

            // Get info from url
            $params = [
                'page'          => $this->params('page', 1),
                'title'         => $this->params('title'),
                'code'          => $this->params('code'),
                'category'      => $this->params('category'),
                'categoryTitle' => $this->params('categoryTitle'),
                'tag'           => $this->params('tag'),
                'favourite'     => $this->params('favourite'),
                'recommended'   => $this->params('recommended'),
                'limit'         => $this->params('limit'),
                'order'         => $this->params('order'),
                'related'       => $this->params('related'),
                'product'       => $this->params('product'),
            ];

            // Get product list
            $productList = Pi::api('api', 'shop')->productList($params);

            // Set result
            $result = [
                'result' => true,
                'data'   => $productList,
                'error'  => [],
            ];

        } else {
            // Set error
            $result['error'] = [
                'code'    => 2,
                'message' => $check['message'],
            ];
        }

        // Return result
        return $result;
    }

    public function singleAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'    => 1,
                'message' => __('Nothing selected'),
            ],
        ];

        // Get info from url
        $token  = $this->params('token');
        $id     = $this->params('id');

        // Check token
        $check = Pi::api('token', 'tools')->check($token);
        if ($check['status'] == 1) {

            // Save statistics
            if (Pi::service('module')->isActive('statistics')) {
                Pi::api('log', 'statistics')->save(
                    'shop', 'productSingle', $this->params('id'), [
                        'source'  => $this->params('platform'),
                        'section' => 'api',
                    ]
                );
            }

            // Check id
            if (intval($id) > 0) {
                $result['data'] = Pi::api('product', 'shop')->getProduct(intval($id));

                // Update hits
                $this->getModel('product')->increment('hits', ['id' => $result['data']['id']]);

                // Attribute
                $result['data']['attributeList'] = [];
                if ($result['data']['attribute']) {
                    $result['data']['attributeList'] = Pi::api('attribute', 'product')->Product($result['data']['id'], $result['data']['category_main']);
                }

                // Check data
                if (!empty($result['data'])) {
                    $result['result'] = true;
                } else {
                    // Set error
                    $result['error'] = [
                        'code'    => 4,
                        'message' => __('Data is empty'),
                    ];
                }
            } else {
                // Set error
                $result['error'] = [
                    'code'    => 3,
                    'message' => __('Id not selected'),
                ];
            }
        } else {
            // Set error
            $result['error'] = [
                'code'    => 2,
                'message' => $check['message'],
            ];
        }

        // Check final result
        if ($result['result']) {
            $result['error'] = [];
        }

        // Return result
        return $result;
    }
}