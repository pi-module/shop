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
use Pi\Form\Form as BaseForm;

class SaleForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new SaleFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // product
        if ($this->option['type'] == 'add') {
            switch ($this->option['part']) {
                case 'product':
                    $this->add(
                        [
                            'name'       => 'product',
                            'options'    => [
                                'label' => __('Product id'),
                            ],
                            'attributes' => [
                                'type'        => 'text',
                                'description' => __('Select product for add to sale'),
                                'required'    => true,
                            ],
                        ]
                    );
                    break;

                case 'category':
                    $this->add(
                        [
                            'name'       => 'category',
                            'options'    => [
                                'label' => __('Category id'),
                            ],
                            'attributes' => [
                                'type'        => 'text',
                                'description' => __('Select category for add to sale'),
                                'required'    => true,
                            ],
                        ]
                    );
                    break;
            }
        }
        // Check part
        switch ($this->option['part']) {
            case 'product':
                // price
                $this->add(
                    [
                        'name'       => 'price',
                        'options'    => [
                            'label' => __('Price'),
                        ],
                        'attributes' => [
                            'type'        => 'text',
                            'description' => __('Real price'),
                        ],
                    ]
                );
                break;

            case 'category':
                // percent
                $this->add(
                    [
                        'name'       => 'percent',
                        'options'    => [
                            'label' => __('Percent'),
                        ],
                        'attributes' => [
                            'type'        => 'text',
                            'description' => __('Discount percent'),
                        ],
                    ]
                );
                break;
        }
        // status
        $this->add(
            [
                'name'       => 'status',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Published'),
                        2 => __('Pending review'),
                        3 => __('Draft'),
                        4 => __('Private'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );
        // time_publish
        $this->add(
            [
                'name'       => 'time_publish',
                'option'     => [
                    'label' => __('Sale publish time'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                ],
            ]
        );
        // time_expire
        $this->add(
            [
                'name'       => 'time_expire',
                'option'     => [
                    'label' => __('Sale expire time'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                ],
            ]
        );
        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'class' => 'btn btn-primary',
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}