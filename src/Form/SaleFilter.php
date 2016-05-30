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

class SaleFilter extends InputFilter
{
    public function __construct($option)
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // product
        if ($option['type'] == 'add') {
            $this->add(array(
                'name' => 'product',
                'required' => true,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
                'validators' => array(
                    new \Module\Shop\Validator\SaleDuplicate(array(
                        'module' => Pi::service('module')->current(),
                        'table' => 'sale',
                    )),
                ),
            ));
        }
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
        // time_publish
        $this->add(array(
            'name' => 'time_publish',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Shop\Validator\TimeSelect,
            ),
        ));
        // time_expire
        $this->add(array(
            'name' => 'time_expire',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Shop\Validator\TimeSelect,
            ),
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'required' => true,
        ));
    }
}