<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class PromotionForm extends BaseForm
{
    public function __construct($name = null, $option = array())
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
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => __('Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => true,
            )
        ));
        // code
        $this->add(array(
            'name' => 'code',
            'options' => array(
                'label' => __('Code'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => true,
            )
        ));
        // type
        $this->add(array(
            'name' => 'type',
            'type' => 'select',
            'options' => array(
                'label' => __('Type'),
                'value_options' => array(
                    '' => '',
                    'percent' => __('Percent'),
                    'price' => __('Price'),
                ),
            ),
            'attributes' => array(
                'required' => true,
                'class' => 'promotion-type',
            )
        ));
        // price
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => __('Customer price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Basket price will be charged'),
            )
        ));
        // price_partner
        $this->add(array(
            'name' => 'price_partner',
            'options' => array(
                'label' => __('Partner price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Basket price will be charged'),
            )
        ));
        // percent
        $this->add(array(
            'name' => 'percent',
            'options' => array(
                'label' => __('Customer percent'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Number and between 1 to 99'),
            )
        ));
        // percent_partner
        $this->add(array(
            'name' => 'percent_partner',
            'options' => array(
                'label' => __('Partner percent'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Number and between 1 to 99'),
            )
        ));
        // time_publish
        $this->add(array(
            'name' => 'time_publish',
            'options' => array(
                'label' => __('Time publish'),
            ),
            'attributes' => array(
                'type' => 'text',
            )
        ));
        // time_expire
        $this->add(array(
            'name' => 'time_expire',
            'options' => array(
                'label' => __('Time expire'),
            ),
            'attributes' => array(
                'type' => 'text',
            )
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => __('Status'),
                'value_options' => array(
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                    5 => __('Delete'),
                ),
            ),
            'attributes' => array(
                'required' => true,
            )
        ));
        // partner
        $this->add(array(
            'name' => 'uid',
            'options' => array(
                'label' => __('Partner uid'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Join partner user id for this promotion to save information of this promotion and user action'),
            )
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}