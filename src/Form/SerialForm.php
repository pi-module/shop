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

class SerialForm extends BaseForm
{
    public function __construct($name = null, $option = [])
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
        $this->add(
            [
                'name'       => 'serial_number',
                'options'    => [
                    'label' => __('Serial number'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Input serial number here'),
                    'required'    => true,
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
                    'value' => __('Check'),
                ],
            ]
        );
    }
}