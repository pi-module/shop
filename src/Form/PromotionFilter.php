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

class PromotionFilter extends InputFilter
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
        // code
        $this->add(array(
            'name' => 'code',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Shop\Validator\CodeDuplicate(array(
                    'module' => Pi::service('module')->current(),
                    'table' => 'promotion',
                )),
            ),
        ));
        // type
        $this->add(array(
            'name' => 'type',
            'required' => true,
        ));
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
        // price_partner
        $this->add(array(
            'name' => 'price_partner',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // percent
        $this->add(array(
            'name' => 'percent',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Shop\Validator\Percent,
            ),
        ));
        // percent_partner
        $this->add(array(
            'name' => 'percent_partner',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Shop\Validator\Percent,
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
        // partner
        $this->add(array(
            'name' => 'partner',
            'required' => false,
        ));
    }
}