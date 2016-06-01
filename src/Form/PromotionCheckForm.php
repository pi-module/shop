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

class PromotionCheckForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new PromotionCheckFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // code
        $this->add(array(
            'name' => 'code',
            'options' => array(
                'label' => __('Promotion code'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => true,
                'class' => 'input-sm',
                'placeholder' => __('Input promotion code'),
            )
        ));
        // Proceeding
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Proceeding code'),
                'class' => 'btn btn-primary btn-xs input-sm',
            )
        ));
    }
}