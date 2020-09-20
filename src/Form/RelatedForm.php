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

class RelatedForm extends BaseForm
{

    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new RelatedFilter;
        }
        return $this->filter;
    }

    public function init()
    {
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

                ],
            ]
        );
        // status
        $this->add(
            [
                'name'    => 'type',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Title search type'),
                    'value_options' => [
                        1 => __('Included'),
                        2 => __('Start with'),
                        3 => __('End with'),
                        4 => __('According'),
                    ],
                ],
            ]
        );
        // category
        $this->add(
            [
                'name'    => 'category',
                'type'    => 'Module\Shop\Form\Element\Category',
                'options' => [
                    'label'    => __('Category'),
                    'category' => [],
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
