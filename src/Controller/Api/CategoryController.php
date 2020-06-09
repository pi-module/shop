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

class CategoryController extends ApiController
{
    public function listAction()
    {
        // Set default result
        $result = [
            'result' => false,
            'data'   => [],
            'error'  => [
                'code'        => 1,
                'message'     => __('Nothing selected'),
                'messageFlag' => false,
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
                    'shop', 'categoryList', 0, [
                        'source'  => $this->params('platform'),
                        'section' => 'api',
                    ]
                );
            }

            // Get data
            $result['data'] = Pi::api('api', 'shop')->categoryList();

            // Check data
            if (!empty($result['data'])) {
                $result = [
                    'result' => true,
                    'data'   => array_values($result['data']),
                    'error'  => [],
                ];
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
                'code'    => 2,
                'message' => $check['message'],
            ];
        }

        // Return result
        return $result;
    }
}