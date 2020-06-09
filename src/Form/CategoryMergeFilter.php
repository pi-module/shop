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

class CategoryMergeFilter extends InputFilter
{
    public function __construct($option)
    {
        // category_from_1
        $this->add(
            [
                'name'       => 'category_from_1',
                'required'   => true,
                'validators' => [
                    new \Module\Shop\Validator\Category,
                ],
            ]
        );
        // category_from_2
        $this->add(
            [
                'name'       => 'category_from_2',
                'required'   => true,
                'validators' => [
                    new \Module\Shop\Validator\Category,
                ],
            ]
        );
        // where_type
        $this->add(
            [
                'name'     => 'where_type',
                'required' => true,
            ]
        );
        // category_to
        $this->add(
            [
                'name'       => 'category_to',
                'required'   => true,
                'validators' => [
                    new \Module\Shop\Validator\Category,
                ],
            ]
        );
    }
}