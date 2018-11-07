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

class PromotionForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new PromotionFilter;
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
        // code
        $this->add(
            [
                'name'       => 'code',
                'options'    => [
                    'label' => __('Code'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );
        // type
        $this->add(
            [
                'name'       => 'type',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Type'),
                    'value_options' => [
                        ''        => '',
                        'percent' => __('Percent'),
                        'price'   => __('Price'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                    'class'    => 'promotion-type',
                ],
            ]
        );
        // price
        $this->add(
            [
                'name'       => 'price',
                'options'    => [
                    'label' => __('Customer price'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Basket price will be charged'),
                ],
            ]
        );
        // price_partner
        $this->add(
            [
                'name'       => 'price_partner',
                'options'    => [
                    'label' => __('Partner price'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Basket price will be charged'),
                ],
            ]
        );
        // percent
        $this->add(
            [
                'name'       => 'percent',
                'options'    => [
                    'label' => __('Customer percent'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Number and between 1 to 99'),
                ],
            ]
        );
        // percent_partner
        $this->add(
            [
                'name'       => 'percent_partner',
                'options'    => [
                    'label' => __('Partner percent'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Number and between 1 to 99'),
                ],
            ]
        );
        // time_publish
        $this->add(
            [
                'name'       => 'time_publish',
                'options'    => [
                    'label' => __('Time publish'),
                ],
                'attributes' => [
                    'type' => 'text',
                ],
            ]
        );
        // time_expire
        $this->add(
            [
                'name'       => 'time_expire',
                'options'    => [
                    'label' => __('Time expire'),
                ],
                'attributes' => [
                    'type' => 'text',
                ],
            ]
        );
        // status
        $this->add(
            [
                'name'       => 'status',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Published'),
                        2 => __('Pending review'),
                        3 => __('Draft'),
                        4 => __('Private'),
                        5 => __('Delete'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );
        // partner
        $this->add(
            [
                'name'       => 'partner',
                'options'    => [
                    'label' => __('Partner uid'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Join partner user id for this promotion to save information of this promotion and user action'),
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