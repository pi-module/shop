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

namespace Module\Shop\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class ProductPriceForm extends BaseForm
{
    public function __construct($name = null, $option = array())
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
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
                'value' => $this->option['id'],
            ),
        ));
        // type
        $this->add(array(
            'name' => 'type',
            'attributes' => array(
                'type' => 'hidden',
                'value' => $this->option['type'],
            ),
        ));
        // Check type
        switch ($this->option['type']) {
            case 'property':
                $fieldCount = 0;
                foreach ($this->option['property'] as $key => $propertyDetails) {
                    if ($this->option['propertyList'][$key]['influence_price']) {
                        foreach ($propertyDetails as $propertySingle) {
                            // id
                            $this->add(array(
                                'name' => sprintf('property-%s-id',  $propertySingle['id']),
                                'attributes' => array(
                                    'type' => 'hidden',
                                    'value' => $propertySingle['id'],
                                ),
                            ));
                            // price
                            $this->add(array(
                                'name' => sprintf('property-%s-price',  $propertySingle['id']),
                                'options' => array(
                                    'label' => $propertySingle['name'],
                                ),
                                'attributes' => array(
                                    'type' => 'text',
                                    'value' => $propertySingle['price'],
                                )
                            ));
                            // Update field count
                            $fieldCount++;
                        }
                    }
                }
                // Check  field count
                if ($fieldCount == 0) {
                    // price
                    $this->add(array(
                        'name' => 'price',
                        'options' => array(
                            'label' => __('Price'),
                        ),
                        'attributes' => array(
                            'type' => 'text',
                            'value' => $this->option['price'],
                        )
                    ));
                }
                break;

            case 'product':
                // price
                $this->add(array(
                    'name' => 'price',
                    'options' => array(
                        'label' => __('Price'),
                    ),
                    'attributes' => array(
                        'type' => 'text',
                        'value' => $this->option['price'],
                    )
                ));
                break;
        }
        // price_discount
        $this->add(array(
            'name' => 'price_discount',
            'options' => array(
                'label' => __('Price Discount'),
            ),
            'attributes' => array(
                'type' => 'text',
                'value' => $this->option['price_discount'],
            )
        ));
        // price_shipping
        $this->add(array(
            'name' => 'price_shipping',
            'options' => array(
                'label' => __('Extra shipping price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'value' => $this->option['price_shipping'],
            )
        ));
        // stock_type
        if ($this->option['order_stock'] == 'manual') {
            $this->add(array(
                'name' => 'stock_type',
                'type' => 'select',
                'options' => array(
                    'label' => __('Stock type'),
                    'value_options' => array(
                        1 => __('In stock'),
                        2 => __('Out of stock'),
                        3 => __('Coming soon'),
                        4 => __('Contact'),
                        5 => __('Variable stock (make order without active payment)'),
                    ),
                ),
                'attributes' => array(
                    'required' => true,
                    'value' => $this->option['stock_type'],
                )
            ));
        } else {
            $this->add(array(
                'name' => 'stock_type',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}