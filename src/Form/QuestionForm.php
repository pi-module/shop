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

class QuestionForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new QuestionFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // product
        $this->add(
            [
                'name'       => 'product',
                'attributes' => [
                    'type'     => 'hidden',
                    'required' => true,
                ],
            ]
        );
        // name
        $this->add(
            [
                'name'       => 'name',
                'options'    => [
                    'label' => __('Name'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );
        // email
        $this->add(
            [
                'name'       => 'email',
                'options'    => [
                    'label' => __('Email'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );
        // text_ask
        $this->add(
            [
                'name'       => 'text_ask',
                'options'    => [
                    'label' => __('Question'),
                ],
                'attributes' => [
                    'type'     => 'textarea',
                    'rows'     => '5',
                    'cols'     => '40',
                    'required' => true,
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
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}