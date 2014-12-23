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
        $this->field = $option['field'];
        $this->config = Pi::service('registry')->config->read('shop', 'search');
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
        // type
        if ($this->config['search_type']) {
            $this->add(array(
                'name' => 'type',
                'type' => 'select',
                'options' => array(
                    'label' => __('Title search type'),
                    'value_options' => array(
                        1 => __('Included'),
                        2 => __('Start with'),
                        3 => __('End with'),
                        4 => __('According'),
                    ),
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'type',
                'attributes' => array(
                    'type' => 'hidden',
                    'value' => 1,
                ),
            ));
        }
        // title
        $this->add(array(
            'name' => 'title',
            'options' => array(
                'label' => __('Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                
            )
        ));
        // price
        if ($this->config['search_price']) {
            // price_from
            $this->add(array(
                'name' => 'price_from',
                'options' => array(
                    'label' => __('Price from'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                    
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
                    
                )
            ));
        } else {
            $this->add(array(
                'name' => 'price_from',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
            $this->add(array(
                'name' => 'price_to',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // category
        if ($this->config['search_category']) {
            $this->add(array(
                'name' => 'category',
                'type' => 'Module\Shop\Form\Element\Category',
                'options' => array(
                    'label' => __('Category'),
                    'category' => '',
                ),
            ));
        } else {
            $this->add(array(
                'name' => 'category',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // Set attribute field
        if (!empty($this->field)) {
            foreach ($this->field as $field) {
                if ($field['search']) {
                    if ($field['type'] == 'select') {
                        $this->add(array(
                            'name' => $field['id'],
                            'type' => 'select',
                            'options' => array(
                                'label' => $field['title'],
                                'value_options' => $this->makeArray($field['value']),
                            ),
                        ));
                    } else {
                        $this->add(array(
                            'name' => $field['id'],
                            'options' => array(
                                'label' => $field['title'],
                            ),
                            'attributes' => array(
                                'type' => 'text',
                            )
                        ));
                    }
                }
            }
        }
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