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

class DiscountForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new DiscountFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add([
            'name'       => 'id',
            'attributes' => [
                'type' => 'hidden',
            ],
        ]);
        // title
        $this->add([
            'name'       => 'title',
            'options'    => [
                'label' => __('Title'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',
                'required'    => true,
            ],
        ]);
        // role
        $this->add([
            'name'       => 'role',
            'type'       => 'Module\Shop\Form\Element\Role',
            'options'    => [
                'label'    => __('User role'),
                'category' => '',
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);
        // category
        $this->add([
            'name'       => 'category',
            'type'       => 'Module\Shop\Form\Element\Company',
            'options'    => [
                'label'    => __('Category'),
                'category' => [0 => ''],
            ],
            'attributes' => [
                'size'     => 1,
                'multiple' => 0,
                'required' => true,
            ],
        ]);
        // percent
        $this->add([
            'name'       => 'percent',
            'options'    => [
                'label' => __('Discount percent'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => __('Number and between 1 to 99'),
                'required'    => true,
            ],
        ]);
        // status
        $this->add([
            'name'       => 'status',
            'type'       => 'select',
            'options'    => [
                'label'         => __('Status'),
                'value_options' => [
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                    5 => __('Delete'),
                ],
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);
        // Save
        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => __('Submit'),
            ],
        ]);
    }
}