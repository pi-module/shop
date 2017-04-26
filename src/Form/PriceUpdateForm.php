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

class PriceUpdateForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new PriceUpdateFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // category
        $this->add(array(
            'name' => 'category',
            'type' => 'Module\Shop\Form\Element\Category',
            'options' => array(
                'label' => __('Main category'),
                'category' => array('' => __('Select category')),
            ),
            'attributes' => array(
                'size' => 1,
                'multiple' => 0,
                'description' => '',
                'required' => true,
            ),
        ));
        // percent
        $this->add(array(
            'name' => 'percent',
            'options' => array(
                'label' => __('Percent update price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Number and between 1 to 99'),
                'required' => true,
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