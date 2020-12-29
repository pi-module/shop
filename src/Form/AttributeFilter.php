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
use Laminas\InputFilter\InputFilter;

class AttributeFilter extends InputFilter
{
    public function __construct($options = [])
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
        // name
        $this->add(
            [
                'name'       => 'name',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Shop\Validator\NameDuplicate(
                        [
                            'module' => Pi::service('module')->current(),
                            'table'  => 'field',
                            'id'     => $options['id'],
                        ]
                    ),
                ],
            ]
        );
        // category
        $this->add(
            [
                'name'     => 'category',
                'required' => true,
            ]
        );
        // status
        $this->add(
            [
                'name'     => 'status',
                'required' => true,
            ]
        );
        // position
        $this->add(
            [
                'name'     => 'position',
                'required' => true,
            ]
        );
        // type
        $this->add(
            [
                'name'     => 'type',
                'required' => true,
            ]
        );

        // data
        $this->add(
            [
                'name'     => 'data',
                'required' => false,
            ]
        );
        // default
        $this->add(
            [
                'name'     => 'default',
                'required' => false,
            ]
        );

        // information
        $this->add(
            [
                'name'     => 'information',
                'required' => false,
            ]
        );
        // icon
        $this->add(
            [
                'name'     => 'icon',
                'required' => false,
            ]
        );
        // search
        $this->add(
            [
                'name'     => 'search',
                'required' => false,
            ]
        );
    }
}
