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

class SaleForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new SaleFilter($this->option);
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
        // product
        if ($this->option['type'] == 'add') {
            $this->add(array(
                'name' => 'product',
                'type' => 'Module\Shop\Form\Element\Product',
                'options' => array(
                    'label' => __('Product'),
                    'limit' => 500,
                    'type' => 'sale',
                ),
                'attributes' => array(
                    'description' => __('Select product for add to sale'),
                    'size' => 1,
                    'multiple' => 0,
                    'required' => true,
                ),
            ));
        }
        // price
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => __('Price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Real price'),
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
                ),
            ),
            'attributes' => array(
                'required' => true,
            )
        ));
        // time_publish
        $this->add(array(
            'name' => 'time_publish',
            'option' => array(
                'label' => __('Sale publish time'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
            )
        ));
        // time_expire
        $this->add(array(
            'name' => 'time_expire',
            'option' => array(
                'label' => __('Sale expire time'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
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