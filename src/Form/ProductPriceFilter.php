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
use Zend\InputFilter\InputFilter;

class ProductPriceFilter extends InputFilter
{
    public function __construct($option = null)
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // type
        $this->add(array(
            'name' => 'type',
            'required' => false,
        ));
        // Check type
        switch ($option['type']) {
            case 'property':
                $fieldCount = 0;
                foreach ($option['property'] as $key => $propertyDetails) {
                    if ($option['propertyList'][$key]['influence_price']) {
                        foreach ($propertyDetails as $propertySingle) {
                            // id
                            $this->add(array(
                                'name' => sprintf('property-%s-id',  $propertySingle['id']),
                                'required' => false,
                            ));
                            // price
                            $this->add(array(
                                'name' => sprintf('property-%s-price',  $propertySingle['id']),
                                'filters' => array(
                                    array(
                                        'name' => 'StringTrim',
                                    ),
                                ),
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
                        'required' => false,
                        'filters' => array(
                            array(
                                'name' => 'StringTrim',
                            ),
                        ),
                    ));
                }
                break;

            case 'product':
                // price
                $this->add(array(
                    'name' => 'price',
                    'required' => false,
                    'filters' => array(
                        array(
                            'name' => 'StringTrim',
                        ),
                    ),
                ));
                break;
        }
        // price_discount
        $this->add(array(
            'name' => 'price_discount',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // price_shipping
        $this->add(array(
            'name' => 'price_shipping',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // stock_type
        $this->add(array(
            'name' => 'stock_type',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
    }
}