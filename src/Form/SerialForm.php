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

class SerialForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new SerialFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // serial_number
        $this->add(array(
            'name' => 'serial_number',
            'options' => array(
                'label' => __('Serial number'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Input serial number here'),
                'required' => true,
            )
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Check'),
            )
        ));
    }
}