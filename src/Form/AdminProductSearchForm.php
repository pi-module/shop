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
    public function __construct($name = null, $option = array())
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
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => __('Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'placeholder' => __('Title / Second title'),
            )
        ));
        // code
        $this->add(array(
            'name' => 'code',
            'options' => array(
                'label' => __('Product code'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'placeholder' => __('Product code'),
            )
        ));
        // category
        $this->add(array(
            'name' => 'category',
            'type' => 'Module\Shop\Form\Element\Category',
            'options' => array(
                'label' => __('Category'),
                //'category' => $this->category,
            ),
            'attributes' => array(
                'size' => 1,
                'multiple' => 0,
            ),
        ));
        // brand
        $this->add(array(
            'name' => 'brand',
            'type' => 'Module\Shop\Form\Element\Brand',
            'options' => array(
                'label' => __('Brand'),
                //'category' => $this->category,
            ),
            'attributes' => array(
                'size' => 1,
                'multiple' => 0,
            ),
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => __('Status'),
                'value_options' => array(
                    '' => __('All status'),
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                    5 => __('Delete'),
                ),
            ),
        ));
        // recommended
        $this->add(array(
            'name' => 'recommended',
            'type' => 'select',
            'options' => array(
                'label' => __('Recommended'),
                'value_options' => array(
                    '' => __('All'),
                    1 => __('Recommended'),
                ),
            ),
        ));
        // sort
        $this->add(array(
            'name' => 'sort',
            'type' => 'select',
            'options' => array(
                'label' => __('Sort order'),
                'value_options' => array(
                    'title' => __('Title DESC'),
                    'titleASC' => __('Title ASC'),
                    'hits' => __('Hits DESC'),
                    'hitsASC' => __('Hits ASC'),
                    'create' => __('Create DESC'),
                    'createASC' => __('Create ASC'),
                    'update' => __('Update DESC'),
                    'updateASC' => __('Update ASC'),
                    'price' => __('Price DESC'),
                    'priceASC' => __('Price ASC'),
                    'stock' => __('Stock DESC'),
                    'stockASC' => __('Stock ASC'),
                    'sold' => __('Sold DESC'),
                ),
            ),
        ));
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Search'),
            )
        ));
    }
}