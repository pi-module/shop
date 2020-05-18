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

class ProductPriceFilter extends InputFilter
{
    public function __construct($option = null)
    {
        // type
        $this->add(
            [
                'name'     => 'type',
                'required' => false,
            ]
        );
        // Check type
        switch ($option['type']) {
            case 'property':
                $fieldCount = 0;
                foreach ($option['property'] as $key => $propertyDetails) {
                    if ($option['propertyList'][$key]['influence_price']) {
                        foreach ($propertyDetails as $propertySingle) {
                            // id
                            $this->add(
                                [
                                    'name'     => sprintf('property-%s-id', $propertySingle['id']),
                                    'required' => false,
                                ]
                            );
                            // id
                            $this->add(
                                [
                                    'name'     => sprintf('property-%s-key', $propertySingle['id']),
                                    'required' => false,
                                ]
                            );
                            // price
                            $this->add(
                                [
                                    'name'    => sprintf('property-%s-price', $propertySingle['id']),
                                    'filters' => [
                                        [
                                            'name' => 'StringTrim',
                                        ],
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
                            'name'     => 'price',
                            'required' => false,
                            'filters'  => [
                                [
                                    'name' => 'StringTrim',
                                ],
                            ],
                        ]
                    );
                }
                break;

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
        }
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
        // stock_type
        $this->add(
            [
                'name'     => 'stock_type',
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