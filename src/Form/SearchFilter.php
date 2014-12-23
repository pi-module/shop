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
    public function __construct($attribute = null)
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
        // Set attribute field
        if (!empty($attribute)) {
            foreach ($attribute as $field) {
                if ($field['search']) {
                    $this->add(array(
                        'name' => $field['id'],
                        'required' => false,
                    ));
                }
            }
        }
    }
}    	