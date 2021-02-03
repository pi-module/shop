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
use Pi\Form\Form as BaseForm;

class CustomerManageForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option    = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new CustomerManageFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
// title
        $this->add(
            [
                'name'       => 'title',
                'options'    => [
                    'label' => __('Title'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // text_summary
        $this->add(
            [
                'name'       => 'text_summary',
                'options'    => [
                    'label' => __('Summary'),
                ],
                'attributes' => [
                    'type'        => 'textarea',
                    'rows'        => '5',
                    'cols'        => '40',
                    'description' => __('Keep summery short, 2 or 3 lines'),
                ],
            ]
        );

        // text_description
        $this->add(
            [
                'name'       => 'text_description',
                'options'    => [
                    'label'  => __('Description'),
                    'editor' => 'html',
                ],
                'attributes' => [
                    'type'        => 'editor',
                    'description' => '',
                ],
            ]
        );

        // category_main
        $this->add(
            [
                'name'       => 'category_main',
                'type'       => 'Module\Shop\Form\Element\Category',
                'options'    => [
                    'label'    => __('Category'),
                    'category' => [],
                ],
                'attributes' => [
                    'size'        => 1,
                    'multiple'    => 0,
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // brand
        if ($this->option['brand_system']) {
            $this->add(
                [
                    'name'       => 'brand',
                    'type'       => 'Module\Shop\Form\Element\Brand',
                    'options'    => [
                        'label'    => __('Brand'),
                        'category' => $this->category,
                    ],
                    'attributes' => [
                        'size'     => 1,
                        'multiple' => 0,
                        'required' => false,
                    ],
                ]
            );
        }

        // main_image
        $this->add(
            [
                'name'    => 'main_image',
                'type'    => 'Module\Media\Form\Element\Media',
                'options' => [
                    'label'  => __('Main image'),
                    'module' => 'shop',
                ],
            ]
        );

        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'class' => 'btn btn-primary',
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}