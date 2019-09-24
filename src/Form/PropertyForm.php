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

class PropertyForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new PropertyFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add(
            [
                'name'       => 'id',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]
        );
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
        // order
        $this->add(
            [
                'name'       => 'order',
                'options'    => [
                    'label' => __('Order'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',

                ],
            ]
        );
        // status
        $this->add(
            [
                'name'    => 'status',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Published'),
                        2 => __('Pending review'),
                        3 => __('Draft'),
                        4 => __('Private'),
                        5 => __('Delete'),
                    ],
                ],
            ]
        );
        // influence_stock
        $this->add(
            [
                'name'       => 'influence_stock',
                'type'       => 'checkbox',
                'options'    => [
                    'label' => __('Influence stock'),
                ],
                'attributes' => [
                    'description' => '',
                ],
            ]
        );
        // influence_price
        $this->add(
            [
                'name'       => 'influence_price',
                'type'       => 'checkbox',
                'options'    => [
                    'label' => __('Influence price'),
                ],
                'attributes' => [
                    'description' => '',
                ],
            ]
        );
        // type
        $this->add(
            [
                'name'    => 'type',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Type'),
                    'value_options' => [
                        'checkbox'  => __('CheckBox'),
                        'selectbox' => __('SelectBox'),
                    ],
                ],
            ]
        );
        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}