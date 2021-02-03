<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Form;

use Pi;
use Laminas\InputFilter\InputFilter;

class CustomerManageFilter extends InputFilter
{
    public function __construct($option = [])
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

        // text_summary
        $this->add(
            [
                'name'     => 'text_summary',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );

        // text_description
        $this->add(
            [
                'name'     => 'text_description',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );

        // category_main
        $this->add(
            [
                'name'       => 'category_main',
                'required'   => true,
                'validators' => [
                    new \Module\Shop\Validator\Category,
                ],
            ]
        );

        // brand
        if ($option['brand_system']) {
            $this->add(
                [
                    'name'     => 'brand',
                    'required' => false,
                ]
            );
        }

        // main_image
        $this->add(
            [
                'name'     => 'main_image',
                'required' => false,
            ]
        );

    }
}