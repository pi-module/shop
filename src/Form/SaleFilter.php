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
use Laminas\InputFilter\InputFilter;

class SaleFilter extends InputFilter
{
    public function __construct($option= [])
    {
        // product
        if ($option['type'] == 'add') {
            switch ($option['part']) {
                case 'product':
                    $this->add(
                        [
                            'name'       => 'product',
                            'required'   => true,
                            'filters'    => [
                                [
                                    'name' => 'StringTrim',
                                ],
                            ],
                            'validators' => [
                                new \Module\Shop\Validator\SaleDuplicate(
                                    [
                                        'module' => Pi::service('module')->current(),
                                        'table'  => 'sale',
                                        'type'   => 'product',
                                    ]
                                ),
                            ],
                        ]
                    );
                    break;

                case 'category':
                    $this->add(
                        [
                            'name'       => 'category',
                            'required'   => true,
                            'filters'    => [
                                [
                                    'name' => 'StringTrim',
                                ],
                            ],
                            'validators' => [
                                new \Module\Shop\Validator\SaleDuplicate(
                                    [
                                        'module' => Pi::service('module')->current(),
                                        'table'  => 'sale',
                                        'type'   => 'category',
                                    ]
                                ),
                            ],
                        ]
                    );
                    break;
            }
        }
        // Check part
        switch ($option['part']) {
            case 'product':
                // price
                $this->add(
                    [
                        'name'     => 'price',
                        'required' => false,
                        'filters'  => [
                            [
                                'name' => 'StringTrim',
                            ],
                        ],
                    ]
                );
                break;

            case 'category':
                // percent
                $this->add(
                    [
                        'name'     => 'percent',
                        'required' => false,
                        'filters'  => [
                            [
                                'name' => 'StringTrim',
                            ],
                        ],
                    ]
                );
                break;
        }
        // time_publish
        $this->add(
            [
                'name'       => 'time_publish',
                'required'   => false,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Shop\Validator\TimeSelect,
                ],
            ]
        );
        // time_expire
        $this->add(
            [
                'name'       => 'time_expire',
                'required'   => false,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Shop\Validator\TimeSelect,
                ],
            ]
        );
        // status
        $this->add(
            [
                'name'     => 'status',
                'required' => true,
            ]
        );
    }
}
