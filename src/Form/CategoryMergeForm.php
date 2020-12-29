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
use Pi\Form\Form as BaseForm;

class CategoryMergeForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new CategoryMergeFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // category_from_1
        $this->add(
            [
                'name'       => 'category_from_1',
                'type'       => 'Module\Shop\Form\Element\Category',
                'options'    => [
                    'label'    => __('From category'),
                    'category' => ['' => __('Please select category')],
                ],
                'attributes' => [
                    'size'     => 1,
                    'multiple' => 0,
                    'required' => true,
                ],
            ]
        );
        // category_from_2
        $this->add(
            [
                'name'       => 'category_from_2',
                'type'       => 'Module\Shop\Form\Element\Category',
                'options'    => [
                    'label'    => __('From category'),
                    'category' => ['' => __('Please select category')],
                ],
                'attributes' => [
                    'size'     => 1,
                    'multiple' => 0,
                    'required' => true,
                ],
            ]
        );
        // where_type
        $this->add(
            [
                'name'       => 'where_type',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Where_type'),
                    'value_options' => [
                        'and' => 'AND',
                        'or'  => 'OR',
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );
        // category_main
        $this->add(
            [
                'name'       => 'category_to',
                'type'       => 'Module\Shop\Form\Element\Category',
                'options'    => [
                    'label'    => __('To category'),
                    'category' => ['' => __('Please select category')],
                ],
                'attributes' => [
                    'size'     => 1,
                    'multiple' => 0,
                    'required' => true,
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
