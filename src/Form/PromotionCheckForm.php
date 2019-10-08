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

class PromotionCheckForm extends BaseForm
{
    public function __construct($name = null, $option = [])
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
        $this->add(
            [
                'name'       => 'code',
                'options'    => [
                    'label' => __('Promotion code'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                    'class'       => 'form-control-sm',
                    'placeholder' => __('Input promotion code'),
                ],
            ]
        );
        // Proceeding
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'class' => 'btn btn-primary btn-sm form-control-sm',
                    'value' => __('Proceeding code'),
                ],
            ]
        );
    }
}