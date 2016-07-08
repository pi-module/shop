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
            switch ($option['part']) {
                case 'product':
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
                                'type' => 'product',
                            )),
                        ),
                    ));
                    break;

                case 'category':
                    $this->add(array(
                        'name' => 'category',
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
                                'type' => 'category',
                            )),
                        ),
                    ));
                    break;
            }
        }

        // Check part
        switch ($option['part']) {
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

            case 'category':
                // percent
                $this->add(array(
                    'name' => 'percent',
                    'required' => false,
                    'filters' => array(
                        array(
                            'name' => 'StringTrim',
                        ),
                    ),
                ));
                break;
        }
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