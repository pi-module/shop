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

class PropertyFilter extends InputFilter
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
        // order
        $this->add(
            [
                'name'     => 'order',
                'required' => false,
            ]
        );
        // status
        $this->add(
            [
                'name'     => 'status',
                'required' => false,
            ]
        );
        // influence_stock
        $this->add(
            [
                'name'     => 'influence_stock',
                'required' => false,
            ]
        );
        // influence_price
        $this->add(
            [
                'name'     => 'influence_price',
                'required' => false,
            ]
        );
        // type
        $this->add(
            [
                'name'     => 'type',
                'required' => false,
            ]
        );
    }
}