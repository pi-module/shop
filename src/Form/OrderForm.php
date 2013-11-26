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

class OrderForm  extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new OrderFilter;
        }
        return $this->filter;
    }

    public function init()
    {
    	// first_name
        $this->add(array(
            'name' => 'first_name',
            'options' => array(
                'label' => __('First Name'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // last_name
        $this->add(array(
            'name' => 'last_name',
            'options' => array(
                'label' => __('Last name'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // email
        $this->add(array(
            'name' => 'email',
            'options' => array(
                'label' => __('Email'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // phone
        $this->add(array(
            'name' => 'phone',
            'options' => array(
                'label' => __('Phone'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // mobile
        $this->add(array(
            'name' => 'mobile',
            'options' => array(
                'label' => __('Mobile'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // company
        $this->add(array(
            'name' => 'company',
            'options' => array(
                'label' => __('Company'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // address
        $this->add(array(
            'name' => 'address',
            'options' => array(
                'label' => __('Address'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '3',
                'cols' => '40',
                'class' => 'span6',
                'description' => '',
            )
        ));
        // country
        $this->add(array(
            'name' => 'country',
            'options' => array(
                'label' => __('Country'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // city
        $this->add(array(
            'name' => 'city',
            'options' => array(
                'label' => __('City'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // zip_code
        $this->add(array(
            'name' => 'zip_code',
            'options' => array(
                'label' => __('Zip code'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Next'),
                'class' => 'btn btn-primary',
            )
        ));
    }
}