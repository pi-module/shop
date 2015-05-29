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

class ProductAdditionalForm extends BaseForm
{
    public function __construct($name = null, $option = array())
    {
        $this->option = $option;
        $this->field = $option['field'];
        $this->position = Pi::api('attribute', 'shop')->attributePositionForm();
        $this->module = Pi::service('module')->current();
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ProductAdditionalFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type' => 'hidden',
            ),
        ));
        // Set fieldset
        $this->add(array(
            'name' => 'extra_order',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Order information'),
            ),
        ));
        // property_color
        $this->add(array(
            'name' => 'propertyColor',
            'type' => 'Module\Shop\Form\Element\PropertyColor',
            'options' => array(
                'label' => __('Add color'),
            ),
        ));
        // Set color
        $color = '';
        if (isset($this->option['color']) && !empty($this->option['color'])) {
            foreach ($this->option['color'] as $key => $value) {
                $colorTemplate =<<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-8">Color<input class="form-control" type="text" value="%s" name="property_color[%s][color]"/></span>
    <span class="col-sm-3">Number<input class="form-control" type="text" value="%s" name="property_color[%s][number]"/></span>
    <a href="#" class="remove_property_color col-sm-1 btn btn-link btn-xs"><i class="fa fa-trash"></i></a>
</div>
EOT;
                $colorTemplate = sprintf($colorTemplate, $value['color'], $key, $value['number'], $key);
                $color = sprintf('%s %s', $colorTemplate, $color);
            }
        }
        // property_color_list
        $this->add(array(
            'name' => 'propertyColorList',
            'type' => 'description',
            'options' => array(
                'label' => __('Color list'),
            ),
            'attributes' => array(
                'description' => sprintf('<div class="property-color-list">%s</div>', $color),
            )
        ));
        // property_warranty
        $this->add(array(
            'name' => 'propertyWarranty',
            'type' => 'Module\Shop\Form\Element\PropertyWarranty',
            'options' => array(
                'label' => __('Add warranty'),
            ),
        ));
        // Set warranty
        $warranty = '';
        if (isset($this->option['warranty']) && !empty($this->option['warranty'])) {
            foreach ($this->option['warranty'] as $value) {
                $warrantyTemplate =<<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-11"><input class="form-control" type="text" value="%s" name="property_warranty[]"/></span>
    <a href="#" class="remove_property_warranty col-sm-1 btn btn-link btn-xs"><i class="fa fa-trash"></i></a>
</div>
EOT;
                $warrantyTemplate = sprintf($warrantyTemplate, $value);
                $warranty = sprintf('%s %s', $warrantyTemplate, $warranty);
            }
        }
        // property_warranty_list
        $this->add(array(
            'name' => 'propertyWarrantyList',
            'type' => 'description',
            'options' => array(
                'label' => __('Warranty list'),
            ),
            'attributes' => array(
                'description' => sprintf('<div class="property-warranty-list">%s</div>', $warranty),
            )
        ));
        // Set attribute field
        if (!empty($this->field)) {
            foreach ($this->position as $key => $value) {
                if (!empty($this->field[$key])) {
                    // Set fieldset
                    $this->add(array(
                        'name' => 'extra_position_' . $key,
                        'type' => 'fieldset',
                        'options' => array(
                            'label' => $value,
                        ),
                    ));
                    // Set list of attributes
                    foreach ($this->field[$key] as $field) {
                        if ($field['type'] == 'select') {
                            $this->add(array(
                                'name' => $field['id'],
                                'type' => 'select',
                                'options' => array(
                                    'label' => $field['title'],
                                    'value_options' => $this->makeArray($field['value']),
                                ),
                            ));
                        } elseif ($field['type'] == 'checkbox') { 
                            $this->add(array(
                                'name' => $field['id'],
                                'type' => 'checkbox',
                                'options' => array(
                                    'label' => $field['title'],
                                ),
                                'attributes' => array()
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
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
                'class' => 'btn btn-primary',
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