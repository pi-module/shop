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

class CategoryFilter extends InputFilter
{
    public function __construct($option)
    {
        // parent
        if ($option['type'] == 'category') {
            $this->add(
                [
                    'name'     => 'parent',
                    'required' => false,
                ]
            );
        }
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
        // slug
        $this->add(
            [
                'name'       => 'slug',
                'required'   => false,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    new \Module\Shop\Validator\SlugDuplicate(
                        [
                            'module' => Pi::service('module')->current(),
                            'table'  => 'category',
                        ]
                    ),
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
        // type
        $this->add(
            [
                'name'     => 'type',
                'required' => true,
            ]
        );
        // display_order
        $this->add(
            [
                'name'     => 'display_order',
                'required' => false,
            ]
        );
        // display_type
        $this->add(
            [
                'name'     => 'display_type',
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
        // image
        $this->add(
            [
                'name'     => 'image',
                'required' => false,
            ]
        );
        // image_wide
        $this->add(
            [
                'name'     => 'image_wide',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // Check is new
        if ($option['isNew']) {
            // percent
            $this->add(
                [
                    'name'     => 'sale_percent',
                    'required' => false,
                    'filters'  => [
                        [
                            'name' => 'StringTrim',
                        ],
                    ],
                ]
            );
            // time_publish
            $this->add(
                [
                    'name'       => 'sale_time_publish',
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
                    'name'       => 'sale_time_expire',
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
        }
        // seo_title
        $this->add(
            [
                'name'     => 'seo_title',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // seo_keywords
        $this->add(
            [
                'name'     => 'seo_keywords',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // seo_description
        $this->add(
            [
                'name'     => 'seo_description',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
    }
}    	