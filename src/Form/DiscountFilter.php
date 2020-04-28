<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
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
        // title
        $this->add(
            [
                'name'     => 'title',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // role
        $this->add(
            [
                'name'     => 'role',
                'required' => true,
            ]
        );
        // category
        $this->add(
            [
                'name'       => 'category',
                'required'   => true,
                'validators' => [
                    new \Module\Shop\Validator\SetValue,
                ],
            ]
        );
        // percent
        $this->add(
            [
                'name'       => 'percent',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Shop\Validator\Percent,
                ],
            ]
        );
        // status
        $this->add(
            [
                'name'     => 'status',
                'required' => true,
            ]
        );
    }
}