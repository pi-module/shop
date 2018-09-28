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

class ProductAdditionalForm extends BaseForm
{
    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        $this->field = $option['field'];
        $this->position = Pi::api('attribute', 'shop')->attributePositionForm();
        $this->property = $option['property'];
        $this->propertyValue = $option['propertyValue'];
        $this->product_ribbon = $option['product_ribbon'];
        $this->video_service = $option['video_service'];
        $this->module = Pi::service('module')->current();
        $this->config = Pi::service('registry')->config->read('shop');
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
        $this->add([
            'name'       => 'id',
            'attributes' => [
                'type' => 'hidden',
            ],
        ]);
        // Set fieldset
        $this->add([
            'name'    => 'extra_order',
            'type'    => 'fieldset',
            'options' => [
                'label' => __('Order information'),
            ],
        ]);
        // extra_product
        /* $this->add(array(
            'name' => 'extra_product',
            'type' => 'fieldset',
            'options' => array(
                'label' => __('Product options'),
            ),
        )); */
        // Set property
        if (isset($this->property) && !empty($this->property)) {
            foreach ($this->property as $property) {
                // Set property information
                $html = '';
                if (isset($this->propertyValue) && !empty($this->propertyValue)) {
                    $i = 30;
                    foreach ($this->propertyValue[$property['id']] as $propertyValue) {
                        if ($property['influence_stock'] && $property['influence_price']) {
                            $htmlTemplate = <<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-4">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][name]"/></span>
    <span class="col-sm-3">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][stock]"/></span>
    <span class="col-sm-3">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][price]"/></span>
    <input type="hidden" value="%s" name="property[%s][%s][unique_key]" />
    <a href="#" class="remove_property_%s col-sm-1 btn btn-link btn-sm"><i class="fa fa-trash"></i></a>
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
                                $propertyValue['unique_key'],
                                $property['id'],
                                $i,
                                $property['id']
                            );
                        } elseif ($property['influence_stock']) {
                            $htmlTemplate = <<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-8">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][name]"/></span>
    <span class="col-sm-3">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][stock]"/></span>
    <input type="hidden" value="%s" name="property[%s][%s][unique_key]" />
    <a href="#" class="remove_property_%s col-sm-1 btn btn-link btn-sm"><i class="fa fa-trash"></i></a>
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
                                $propertyValue['unique_key'],
                                $property['id'],
                                $i,
                                $property['id']
                            );
                        } elseif ($property['influence_price']) {
                            $htmlTemplate = <<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-8">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][name]"/></span>
    <span class="col-sm-3">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][price]"/></span>
    <input type="hidden" value="%s" name="property[%s][%s][unique_key]" />
    <a href="#" class="remove_property_%s col-sm-1 btn btn-link btn-sm"><i class="fa fa-trash"></i></a>
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
                                $propertyValue['unique_key'],
                                $property['id'],
                                $i,
                                $property['id']
                            );
                        } else {
                            $htmlTemplate = <<<'EOT'
<div class="col-sm-12 js-form-element">
    <span class="col-sm-11">%s<input class="form-control" type="text" value="%s" name="property[%s][%s][name]"/></span>
    <input type="hidden" value="%s" name="property[%s][%s][unique_key]" />
    <a href="#" class="remove_property_%s col-sm-1 btn btn-link btn-sm"><i class="fa fa-trash"></i></a>
</div>
EOT;
                            $htmlTemplate = sprintf(
                                $htmlTemplate,
                                __('Name'),
                                $propertyValue['name'],
                                $property['id'],
                                $i,
                                $propertyValue['unique_key'],
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
                $this->add([
                    'name'       => sprintf('property-list-%s', $property['id']),
                    'type'       => 'description',
                    'options'    => [
                        'label' => sprintf(__('%s list'), $property['title']),
                    ],
                    'attributes' => [
                        'description' => sprintf('<div class="property-list-%s">%s</div>', $property['id'], $html),
                    ],
                ]);
                // add property
                $this->add([
                    'name'       => sprintf('property-%s', $property['id']),
                    'type'       => 'Module\Shop\Form\Element\Property',
                    'options'    => [
                        'label' => sprintf(__('Add %s'), $property['title']),
                    ],
                    'attributes' => [
                        'id' => $property['id'],
                    ],
                ]);
            }
        }
        // price
        $this->add([
            'name'       => 'price',
            'options'    => [
                'label' => __('Price'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => __('Real price'),
                'required'    => true,
            ],
        ]);
        // price_discount
        $this->add([
            'name'       => 'price_discount',
            'options'    => [
                'label' => __('Price Discount'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => __('Display prices'),
            ],
        ]);
        // price_shipping
        $this->add([
            'name'       => 'price_shipping',
            'options'    => [
                'label' => __('Extra shipping price'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => __('If your product have special shipping price, you can add it here and this price collected to general order shipping price'),
            ],
        ]);
        // price_title
        $this->add([
            'name'       => 'price_title',
            'options'    => [
                'label' => __('Price title'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',
            ],
        ]);
        // stock
        if ($this->config['order_stock'] == 'product') {
            $this->add([
                'name'       => 'stock',
                'options'    => [
                    'label' => __('Stock'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                ],
            ]);
        } else {
            $this->add([
                'name'       => 'stock',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]);
        }
        // stock_type
        if ($this->config['order_stock'] == 'manual') {
            $this->add([
                'name'       => 'stock_type',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Stock type'),
                    'value_options' => [
                        1 => __('In stock'),
                        2 => __('Out of stock'),
                        3 => __('Coming soon'),
                        4 => __('Contact'),
                        5 => __('Variable stock (make order without active payment)'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]);
        } else {
            $this->add([
                'name'       => 'stock_type',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]);
        }
        // order_discount
        if ($this->config['order_discount']) {
            // extra_product
            $this->add([
                'name'    => 'extra_discount',
                'type'    => 'fieldset',
                'options' => [
                    'label' => __('Discount options'),
                ],
            ]);
            // Get role list
            $roles = Pi::service('registry')->Role->read('front');
            unset($roles['webmaster']);
            unset($roles['guest']);
            foreach ($roles as $name => $role) {
                $this->add([
                    'name'       => $name,
                    'options'    => [
                        'label' => $role['title'],
                    ],
                    'attributes' => [
                        'type'        => 'text',
                        'description' => __('Number and between 1 to 99'),
                    ],
                ]);
            }
        }
        // Set attribute field
        if (!empty($this->field)) {
            foreach ($this->position as $key => $value) {
                if (!empty($this->field[$key])) {
                    // Set fieldset
                    $this->add([
                        'name'    => 'extra_position_' . $key,
                        'type'    => 'fieldset',
                        'options' => [
                            'label' => $value,
                        ],
                    ]);
                    // Set list of attributes
                    foreach ($this->field[$key] as $field) {
                        if ($field['type'] == 'select') {
                            $this->add([
                                'name'    => $field['id'],
                                'type'    => 'select',
                                'options' => [
                                    'label'         => $field['title'],
                                    'value_options' => $this->makeArrayJson($field['value']),
                                ],
                            ]);
                        } elseif ($field['type'] == 'checkbox') {
                            $this->add([
                                'name'       => $field['id'],
                                'type'       => 'checkbox',
                                'options'    => [
                                    'label' => $field['title'],
                                ],
                                'attributes' => [],
                            ]);
                        } else {
                            $this->add([
                                'name'       => $field['id'],
                                'options'    => [
                                    'label' => $field['title'],
                                ],
                                'attributes' => [
                                    'type' => 'text',
                                ],
                            ]);
                        }
                    }
                }
            }
        }
        // ribbon
        $this->add([
            'name'    => 'ribbon',
            'type'    => 'select',
            'options' => [
                'label'         => __('Ribbon'),
                'value_options' => $this->makeArray($this->product_ribbon),
            ],
        ]);
        // Set video service
        if ($this->video_service && Pi::service('module')->isActive('video')) {
            // extra_video
            $this->add([
                'name'    => 'extra_video',
                'type'    => 'fieldset',
                'options' => [
                    'label' => __('Video options'),
                ],
            ]);
            // Set video service
            $this->add([
                'name'       => 'video_list',
                'type'       => 'Module\Video\Form\Element\Service',
                'options'    => [
                    'label' => __('Product video'),
                ],
                'attributes' => [
                    'size'        => 1,
                    'multiple'    => 0,
                    'description' => __('Select related video form video system'),
                    'required'    => false,
                ],
            ]);
        }
        // Save
        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => __('Submit'),
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    public function makeArray($string)
    {
        $list = [];
        $variable = explode('|', $string);
        foreach ($variable as $value) {
            $list[$value] = $value;
        }
        return $list;
    }

    public function makeArrayJson($values)
    {
        $list = [];
        $values = json_decode($values, true);
        $variable = explode('|', $values['data']);
        foreach ($variable as $value) {
            $list[$value] = $value;
        }
        return $list;
    }
}