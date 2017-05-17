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

class CategoryMergeForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->option = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new CategoryMergeFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // category_from_1
        $this->add(array(
            'name' => 'category_from_1',
            'type' => 'Module\Shop\Form\Element\Category',
            'options' => array(
                'label' => __('From category'),
                'category' => array('' => __('Please select category')),
            ),
            'attributes' => array(
                'size' => 1,
                'multiple' => 0,
                'required' => true,
            ),
        ));
        // category_from_2
        $this->add(array(
            'name' => 'category_from_2',
            'type' => 'Module\Shop\Form\Element\Category',
            'options' => array(
                'label' => __('From category'),
                'category' => array('' => __('Please select category')),
            ),
            'attributes' => array(
                'size' => 1,
                'multiple' => 0,
                'required' => true,
            ),
        ));
        // where_type
        $this->add(array(
            'name' => 'where_type',
            'type' => 'select',
            'options' => array(
                'label' => __('Where_type'),
                'value_options' => array(
                    'and' => 'AND',
                    'or' => 'OR',
                ),
            ),
            'attributes' => array(
                'required' => true,
            )
        ));
        // category_main
        $this->add(array(
            'name' => 'category_to',
            'type' => 'Module\Shop\Form\Element\Category',
            'options' => array(
                'label' => __('To category'),
                'category' => array('' => __('Please select category')),
            ),
            'attributes' => array(
                'size' => 1,
                'multiple' => 0,
                'required' => true,
            ),
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