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
        $this->property = $option['property'];
        $this->propertyValue = $option['propertyValue'];
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
        // Set property
        foreach ($this->property as $property) {
            // add property
            $this->add(array(
                'name' => sprintf('property-%s', $property['id']),
                'type' => 'Module\Shop\Form\Element\Property',
                'options' => array(
                    'label' => sprintf(__('Add %s'), $property['title']),
                ),
                'attributes' => array(
                    'id' => $property['id'],
                ),
            ));
            // Set property information
            $html = '';
            if (isset($this->propertyValue) && !empty($this->propertyValue)) {
                $i = 30;
                foreach ($this->propertyValue[$property['id']]  as $propertyValue) {
                    if ($property['influence_stock'] && $property['influence_price']) {
                        $htmlTemplate =<<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-4">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][name]"/></span>
    <span class="col-sm-3">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][stock]"/></span>
    <span class="col-sm-3">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][price]"/></span>
    <a href="#" class="remove_property_%s col-sm-1 btn btn-link btn-xs"><i class="fa fa-trash"></i></a>
</div>
EOT;
                        $htmlTemplate = sprintf(
                            $htmlTemplate,
                            __('Name'),
                            $propertyValue['name'],
                            $property['id'],
                            $i,
                            __('Stock'),
                            $propertyValue['stock'],
                            $property['id'],
                            $i,
                            __('Price'),
                            $propertyValue['price'],
                            $property['id'],
                            $i,
                            $property['id']
                        );
                    } elseif ($property['influence_stock']) {
                        $htmlTemplate =<<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-8">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][name]"/></span>
    <span class="col-sm-3">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][stock]"/></span>
    <a href="#" class="remove_property_%s col-sm-1 btn btn-link btn-xs"><i class="fa fa-trash"></i></a>
</div>
EOT;
                        $htmlTemplate = sprintf(
                            $htmlTemplate,
                            __('Name'),
                            $propertyValue['name'],
                            $property['id'],
                            $i,
                            __('Stock'),
                            $propertyValue['stock'],
                            $property['id'],
                            $i,
                            $property['id']
                        );
                    } elseif ($property['influence_price']) {
                        $htmlTemplate =<<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-8">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][name]"/></span>
    <span class="col-sm-3">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][price]"/></span>
    <a href="#" class="remove_property_%s col-sm-1 btn btn-link btn-xs"><i class="fa fa-trash"></i></a>
</div>
EOT;
                        $htmlTemplate = sprintf(
                            $htmlTemplate,
                            __('Name'),
                            $propertyValue['name'],
                            $property['id'],
                            $i,
                            __('Price'),
                            $propertyValue['price'],
                            $property['id'],
                            $i,
                            $property['id']
                        );
                    } else {
                        $htmlTemplate =<<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-11">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][name]"/></span>
    <a href="#" class="remove_property_%s col-sm-1 btn btn-link btn-xs"><i class="fa fa-trash"></i></a>
</div>
EOT;
                        $htmlTemplate = sprintf(
                            $htmlTemplate,
                            __('Name'),
                            $propertyValue['name'],
                            $property['id'],
                            $i,
                            $property['id']
                        );
                    }
                    $html = sprintf('%s %s', $htmlTemplate, $html);
                    $i++;

                }
            }
            // Set property list
            $this->add(array(
                'name' => sprintf('property-list-%s', $property['id']),
                'type' => 'description',
                'options' => array(
                    'label' => sprintf(__('%s list'), $property['title']),
                ),
                'attributes' => array(
                    'description' => sprintf('<div class="property-list-%s">%s</div>', $property['id'], $html),
                )
            ));
        }
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

    public function makeArray($values)
    {
        $list = array();
        $values = json_decode($values, true);
        $variable = explode('|', $values['data']);
        foreach ($variable as $value) {
            $list[$value] = $value;
        }
        return $list;
    }
}