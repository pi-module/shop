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

class ProductPriceForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ProductPriceFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add(
            [
                'name'       => 'id',
                'attributes' => [
                    'type'  => 'hidden',
                    'value' => $this->option['id'],
                ],
            ]
        );
        // type
        $this->add(
            [
                'name'       => 'type',
                'attributes' => [
                    'type'  => 'hidden',
                    'value' => $this->option['type'],
                ],
            ]
        );
        // Check type
        switch ($this->option['type']) {
            case 'property':
                $fieldCount = 0;
                foreach ($this->option['property'] as $key => $propertyDetails) {
                    if ($this->option['propertyList'][$key]['influence_price']) {
                        foreach ($propertyDetails as $propertySingle) {
                            // id
                            $this->add(
                                [
                                    'name'       => sprintf('property-%s-id', $propertySingle['id']),
                                    'attributes' => [
                                        'type'  => 'hidden',
                                        'value' => $propertySingle['id'],
                                    ],
                                ]
                            );
                            // unique_key
                            $this->add(
                                [
                                    'name'       => sprintf('property-%s-key', $propertySingle['id']),
                                    'attributes' => [
                                        'type'  => 'hidden',
                                        'value' => $propertySingle['unique_key'],
                                    ],
                                ]
                            );
                            // price
                            $this->add(
                                [
                                    'name'       => sprintf('property-%s-price', $propertySingle['id']),
                                    'options'    => [
                                        'label' => $propertySingle['name'],
                                    ],
                                    'attributes' => [
                                        'type'  => 'text',
                                        'value' => $propertySingle['price'],
                                    ],
                                ]
                            );
                            // Update field count
                            $fieldCount++;
                        }
                    }
                }
                // Check  field count
                if ($fieldCount == 0) {
                    // price
                    $this->add(
                        [
                            'name'       => 'price',
                            'options'    => [
                                'label' => __('Price'),
                            ],
                            'attributes' => [
                                'type'  => 'text',
                                'value' => $this->option['price'],
                            ],
                        ]
                    );
                }
                break;

            case 'product':
                // price
                $this->add(
                    [
                        'name'       => 'price',
                        'options'    => [
                            'label' => __('Price'),
                        ],
                        'attributes' => [
                            'type'  => 'text',
                            'value' => $this->option['price'],
                        ],
                    ]
                );
                break;
        }
        // price_discount
        $this->add(
            [
                'name'       => 'price_discount',
                'options'    => [
                    'label' => __('Price Discount'),
                ],
                'attributes' => [
                    'type'  => 'text',
                    'value' => $this->option['price_discount'],
                ],
            ]
        );
        // price_shipping
        $this->add(
            [
                'name'       => 'price_shipping',
                'options'    => [
                    'label' => __('Extra shipping price'),
                ],
                'attributes' => [
                    'type'  => 'text',
                    'value' => $this->option['price_shipping'],
                ],
            ]
        );
        // stock_type
        if ($this->option['order_stock'] == 'manual') {
            $this->add(
                [
                    'name'       => 'stock_type',
                    'type'       => 'select',
                    'options'    => [
                        'label'         => __('Stock type'),
                        'value_options' => [
                            1 => __('In stock'),
                            2 => __('Out of stock'),
                            3 => __('Coming soon'),
                            4 => __('Contact'),
                            5 => __('Variable stock (make order without active payment)'),
                        ],
                    ],
                    'attributes' => [
                        'required' => true,
                        'value'    => $this->option['stock_type'],
                    ],
                ]
            );
        } else {
            $this->add(
                [
                    'name'       => 'stock_type',
                    'attributes' => [
                        'type' => 'hidden',
                    ],
                ]
            );
        }
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