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

class AdminProductSearchForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new AdminProductSearchFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // title
        $this->add([
            'name'       => 'title',
            'options'    => [
                'label' => __('Title'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',
                'placeholder' => __('Title / Second title'),
            ],
        ]);
        // code
        $this->add([
            'name'       => 'code',
            'options'    => [
                'label' => __('Product code'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',
                'placeholder' => __('Product code'),
            ],
        ]);
        // category
        $this->add([
            'name'       => 'category',
            'type'       => 'Module\Shop\Form\Element\Category',
            'options'    => [
                'label' => __('Category'),
                //'category' => $this->category,
            ],
            'attributes' => [
                'size'     => 1,
                'multiple' => 0,
            ],
        ]);
        // brand
        $this->add([
            'name'       => 'brand',
            'type'       => 'Module\Shop\Form\Element\Brand',
            'options'    => [
                'label' => __('Brand'),
                //'category' => $this->category,
            ],
            'attributes' => [
                'size'     => 1,
                'multiple' => 0,
            ],
        ]);
        // status
        $this->add([
            'name'    => 'status',
            'type'    => 'select',
            'options' => [
                'label'         => __('Status'),
                'value_options' => [
                    '' => __('All status'),
                    1  => __('Published'),
                    2  => __('Pending review'),
                    3  => __('Draft'),
                    4  => __('Private'),
                    5  => __('Delete'),
                ],
            ],
        ]);
        // recommended
        $this->add([
            'name'    => 'recommended',
            'type'    => 'select',
            'options' => [
                'label'         => __('Recommended'),
                'value_options' => [
                    '' => __('All'),
                    1  => __('Recommended'),
                ],
            ],
        ]);
        // sort
        $this->add([
            'name'    => 'sort',
            'type'    => 'select',
            'options' => [
                'label'         => __('Sort order'),
                'value_options' => [
                    'title'     => __('Title DESC'),
                    'titleASC'  => __('Title ASC'),
                    'hits'      => __('Hits DESC'),
                    'hitsASC'   => __('Hits ASC'),
                    'create'    => __('Create DESC'),
                    'createASC' => __('Create ASC'),
                    'update'    => __('Update DESC'),
                    'updateASC' => __('Update ASC'),
                    'price'     => __('Price DESC'),
                    'priceASC'  => __('Price ASC'),
                    'stock'     => __('Stock DESC'),
                    'stockASC'  => __('Stock ASC'),
                    'sold'      => __('Sold DESC'),
                ],
            ],
        ]);
        // Save
        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => __('Search'),
            ],
        ]);
    }
}