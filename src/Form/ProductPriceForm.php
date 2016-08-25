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
                foreach ($this->option['property'] as $key => $propertyDetails) {
                    if ($this->option['propertyList'][$key]['influence_price']) {
                        foreach ($propertyDetails as $propertySingle) {
                            // unique_key
                            /* $this->add(array(
                                'name' => sprintf('property-%s-unique_key',  $propertySingle['id']),
                                'attributes' => array(
                                    'type' => 'hidden',
                                    'value' => $propertySingle['unique_key'],
                                ),
                            )); */
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
                        }
                    }
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