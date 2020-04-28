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

namespace Module\Shop\Form;

use Pi;
use Zend\InputFilter\InputFilter;

class ProductAdditionalFilter extends InputFilter
{
    public function __construct($option = [])
    {
        // Get config
        $config = Pi::service('registry')->config->read('shop');
        // Set attribute position
        $position = Pi::api('attribute', 'shop')->attributePositionForm();

        // ribbon
        $this->add(
            [
                'name'     => 'ribbon',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // stock
        $this->add(
            [
                'name'     => 'stock',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // stock_type
        $this->add(
            [
                'name'     => 'stock_type',
                'required' => false,
            ]
        );
        // price
        $this->add(
            [
                'name'     => 'price',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // price_discount
        $this->add(
            [
                'name'     => 'price_discount',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // price_shipping
        $this->add(
            [
                'name'     => 'price_shipping',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // price_title
        $this->add(
            [
                'name'     => 'price_title',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // order_discount
        if ($config['order_discount']) {
            // Get role list
            $roles = Pi::service('registry')->Role->read('front');
            unset($roles['webmaster']);
            unset($roles['guest']);
            foreach ($roles as $name => $role) {
                $this->add(
                    [
                        'name'     => $name,
                        'required' => false,
                        'filters'  => [
                            [
                                'name' => 'StringTrim',
                            ],
                        ],
                    ]
                );
            }
        }
        // Set attribute
        if (!empty($option['field'])) {
            foreach ($position as $key => $value) {
                if (!empty($option['field'][$key])) {
                    foreach ($option['field'][$key] as $field) {
                        $this->add(
                            [
                                'name'     => $field['id'],
                                'required' => false,
                            ]
                        );
                    }
                }
            }
        }
        // Set video service
        if ($option['video_service'] && Pi::service('module')->isActive('video')) {
            $this->add(
                [
                    'name'     => 'video_list',
                    'required' => false,
                    'filters'  => [
                        [
                            'name' => 'StringTrim',
                        ],
                    ],
                ]
            );
        }
    }
}