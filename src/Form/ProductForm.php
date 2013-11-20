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

class ProductForm  extends BaseForm
{
    protected $thumbUrl = '';

    public function __construct($name = null, $option = array())
    {
        $this->property = $option['property'];
        $this->field = $option['field'];
        $this->thumbUrl = (isset($option['thumbUrl'])) ? $option['thumbUrl'] : '';
        $this->removeUrl = (isset($option['removeUrl'])) ? $option['removeUrl'] : '';
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
        // extra_general
        $this->add(array(
            'name' => 'extra_general',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('General options'),
            ),
        ));
        // id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
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
        // slug
        $this->add(array(
            'name' => 'slug',
            'options' => array(
                'label' => __('slug'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // summary
        $this->add(array(
            'name' => 'summary',
            'options' => array(
                'label' => __('Summary'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
                'class' => 'span6',
                'description' => '',
            )
        ));
        // description
        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => __('Description'),
                'editor' => 'html',
            ),
            'attributes' => array(
                'type' => 'editor',
                'description' => '',
            )
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'type' => 'select',
            'options' => array(
                'label' => __('Status'),
                'value_options' => array(
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                ),
            ),
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
        // Image
        if ($this->thumbUrl) {
            $this->add(array(
                'name' => 'imageview',
                'options' => array(
                    'label' => __('Image'),
                ),
                'attributes' => array(
                    'type' => 'image',
                    'src' => $this->thumbUrl,
                    'height' => '200',
                    'disabled' => true,
                    'description' => '',
                )
            ));
            $this->add(array(
                'name' => 'remove',
                'options' => array(
                    'label' => __('Remove image'),
                ),
                'attributes' => array(
                    'type' => 'button',
                    'class' => 'btn btn-danger btn-small',
                    'data-toggle' => 'button',
                    'data-link' => $this->removeUrl,
                )
            ));
            $this->add(array(
                'name' => 'image',
                'attributes' => array(
                    'type' => 'hidden',
                ),
             ));
        } else {
            $this->add(array(
                'name' => 'image',
                'options' => array(
                    'label' => __('Image'),
                ),
                'attributes' => array(
                    'type' => 'file',
                    'description' => '',
                )
            ));
        }
        // extra_product
        $this->add(array(
            'name' => 'extra_product',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Product options'),
            ),
        ));
        // stock
        $this->add(array(
            'name' => 'stock',
            'options' => array(
                'label' => __('Stock'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span3',
            )
        ));
        // stock_alert
        $this->add(array(
            'name' => 'stock_alert',
            'options' => array(
                'label' => __('Stock Alert'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span3',
            )
        ));
        // price
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => __('Price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span3',
            )
        ));
        // price_discount
        $this->add(array(
            'name' => 'price_discount',
            'options' => array(
                'label' => __('Price Discount'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span3',
            )
        ));
        // extra_seo
        $this->add(array(
            'name' => 'extra_seo',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('SEO options'),
            ),
        ));
        // seo_title
        $this->add(array(
            'name' => 'seo_title',
            'options' => array(
                'label' => __('SEO Title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // seo_keywords
        $this->add(array(
            'name' => 'seo_keywords',
            'options' => array(
                'label' => __('SEO Keywords'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // seo_description
        $this->add(array(
            'name' => 'seo_description',
            'options' => array(
                'label' => __('SEO Description'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'class' => 'span6',
            )
        ));
        // tag
        if (Pi::service('module')->isActive('tag')) {
            $this->add(array(
                'name' => 'tag',
                'options' => array(
                    'label' => __('Tags'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                    'class' => 'span6',
                )
            ));
        }
        // extra_property
        $this->add(array(
            'name' => 'extra_property',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Property options'),
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
        // extra_field
        $this->add(array(
            'name' => 'extra_field',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Extra fields'),
            ),
        ));
        // Set extra field
        if (!empty($this->field)) {
            foreach ($this->field as $field) {
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