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

class SearchForm  extends BaseForm
{
	public function __construct($name = null, $option = array())
    {
        $this->property = $option['property'];
        $this->field = $option['field'];
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ProductFilter;
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
                'class' => 'span6',
            )
        ));
        // price_from
        $this->add(array(
            'name' => 'price_from',
            'options' => array(
                'label' => __('Price from'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // price_to
        $this->add(array(
            'name' => 'price_to',
            'options' => array(
                'label' => __('Price to'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // category
        $this->add(array(
            'name' => 'category',
            'type' => 'Module\Shop\Form\Element\Category',
            'options' => array(
                'label' => __('Category'),
                'category' => '',
            ),
        ));
        // property_1
        if (!empty($this->property['property_1_option']['value'])) {
            $this->add(array(
                'name' => 'property_1',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_1_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_1_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_1',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_2
        if (!empty($this->property['property_2_option']['value'])) {
            $this->add(array(
                'name' => 'property_2',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_2_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_2_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_2',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_3
        if (!empty($this->property['property_3_option']['value'])) {
            $this->add(array(
                'name' => 'property_3',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_3_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_3_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_3',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_4
        if (!empty($this->property['property_4_option']['value'])) {
            $this->add(array(
                'name' => 'property_4',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_4_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_4_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_4',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_5
        if (!empty($this->property['property_5_option']['value'])) {
            $this->add(array(
                'name' => 'property_5',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_5_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_5_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_5',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_6
        if (!empty($this->property['property_6_option']['value'])) {
            $this->add(array(
                'name' => 'property_6',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_6_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_6_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_6',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_7
        if (!empty($this->property['property_7_option']['value'])) {
            $this->add(array(
                'name' => 'property_7',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_7_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_7_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_7',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_8
        if (!empty($this->property['property_8_option']['value'])) {
            $this->add(array(
                'name' => 'property_8',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_8_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_8_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_8',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_9
        if (!empty($this->property['property_9_option']['value'])) {
            $this->add(array(
                'name' => 'property_9',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_9_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_9_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_9',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // property_10
        if (!empty($this->property['property_10_option']['value'])) {
            $this->add(array(
                'name' => 'property_10',
                'type' => 'select',
                'options' => array(
                    'label' => $this->property['property_10_title']['value'],
                    'value_options' => $this->makeArray($this->property['property_10_option']['value']),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'property_10',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // Set extra field
        /* if (!empty($this->field)) {
            foreach ($this->field as $field) {
            	if ($field['search']) {
            		$this->add(array(
                    	'name' => $field['id'],
                    	'options' => array(
                        	'label' => $field['title'],
                    	),
                    	'attributes' => array(
                        	'type' => 'text',
                        	'class' => 'span6',
                    	)
                	));
            	}
                
            }
        } */
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }

    public function makeArray($string)
    {
        $list = array();
        $variable = explode('|', $string);
        foreach ($variable as $value) {
            $list[$value] = $value;
        }
        return $list;
    }
}