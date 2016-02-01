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

class DiscountFilter extends InputFilter
{
    public function __construct()
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // role
        $this->add(array(
            'name' => 'role',
            'required' => true,
        ));
        // category
        $this->add(array(
            'name' => 'category',
            'required' => true,
            'validators' => array(
                new \Module\Shop\Validator\SetValue,
            ),
        ));
        // percent
        $this->add(array(
            'name' => 'percent',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Shop\Validator\Percent,
            ),
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'required' => true,
        ));
    }
}