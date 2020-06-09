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

class PriceUpdateFilter extends InputFilter
{
    public function __construct()
    {
        // category
        $this->add(
            [
                'name'       => 'category',
                'required'   => true,
                'validators' => [
                    new \Module\Shop\Validator\Category,
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
    }
}