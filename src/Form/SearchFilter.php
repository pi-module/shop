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

class SearchFilter extends InputFilter
{
    public function __construct($extra = null)
    {
        // type
        $this->add(array(
            'name' => 'type',
            'required' => false,
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // price_from
        $this->add(array(
            'name' => 'price_from',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // price_to
        $this->add(array(
            'name' => 'price_to',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // category
        $this->add(array(
            'name' => 'category',
            'required' => false,
        ));
        // property_1
        $this->add(array(
            'name' => 'property_1',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_2
        $this->add(array(
            'name' => 'property_2',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_3
        $this->add(array(
            'name' => 'property_3',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_4
        $this->add(array(
            'name' => 'property_4',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_5
        $this->add(array(
            'name' => 'property_5',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_6
        $this->add(array(
            'name' => 'property_6',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_7
        $this->add(array(
            'name' => 'property_7',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_8
        $this->add(array(
            'name' => 'property_8',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_9
        $this->add(array(
            'name' => 'property_9',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // property_10
        $this->add(array(
            'name' => 'property_10',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // Set extra field
        /* if (!empty($extra)) {
            foreach ($extra as $field) {
                $this->add(array(
                    'name' => $field['id'],
                    'required' => false,
                ));
            }
        } */
    }
}    	