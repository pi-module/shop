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

class ProductAdditionalFilter extends InputFilter
{
    public function __construct($option = array())
    {
        // Set attribute position
        $position = Pi::api('attribute', 'shop')->attributePositionForm();
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // color
        $this->add(array(
            'name' => 'color',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // warranty
        $this->add(array(
            'name' => 'warranty',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // Set attribute
        if (!empty($option['field'])) {
            foreach ($position as $key => $value) {
                if (!empty($option['field'][$key])) {
                    foreach ($option['field'][$key] as $field) {
                        $this->add(array(
                            'name' => $field['id'],
                            'required' => false,
                        ));
                    }
                }
            }
        }
    }
}    	