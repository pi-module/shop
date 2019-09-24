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

class PromotionFilter extends InputFilter
{
    public function __construct()
    {
        // id
        $this->add(
            [
                'name'     => 'id',
                'required' => false,
            ]
        );
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
        // code
        $this->add(
            [
                'name'       => 'code',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Shop\Validator\CodeDuplicate(
                        [
                            'module' => Pi::service('module')->current(),
                            'table'  => 'promotion',
                        ]
                    ),
                ],
            ]
        );
        // type
        $this->add(
            [
                'name'     => 'type',
                'required' => true,
            ]
        );
        // price
        $this->add(
            [
                'name'     => 'price',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // price_partner
        $this->add(
            [
                'name'     => 'price_partner',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // percent
        $this->add(
            [
                'name'       => 'percent',
                'required'   => false,
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
        // percent_partner
        $this->add(
            [
                'name'       => 'percent_partner',
                'required'   => false,
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
        // time_publish
        $this->add(
            [
                'name'       => 'time_publish',
                'required'   => false,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Shop\Validator\TimeSelect,
                ],
            ]
        );
        // time_expire
        $this->add(
            [
                'name'       => 'time_expire',
                'required'   => false,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Shop\Validator\TimeSelect,
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
        // partner
        $this->add(
            [
                'name'     => 'partner',
                'required' => false,
            ]
        );
    }
}