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
                'type' => 'Module\Shop\Form\Element\Image',
                'options' => array(
                    //'label' => __('Image'),
                ),
                'attributes' => array(
                    'src' => $this->thumbUrl,
                ),
            ));
            $this->add(array(
                'name' => 'remove',
                'type' => 'Module\Shop\Form\Element\Remove',
                'options' => array(
                    'label' => __('Remove image'),
                ),
                'attributes' => array(
                    'link' => $this->removeUrl,
                ),
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
                
            )
        ));
        // price_title
        $this->add(array(
            'name' => 'price_title',
            'options' => array(
                'label' => __('Price title'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                
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
                
            )
        ));
        // tag
        if (Pi::service('module')->isActive('tag')) {
            $this->add(array(
                'name' => 'tag',
                'type' => 'tag',
                'options' => array(
                    'label' => __('Tags'),
                ),
                'attributes' => array(
                    'id'          => 'tag',
                    'description' => __('Use `|` as delimiter to separate tag terms'),
                )
            ));
        }
        // Set extra field
        if (!empty($this->field)) {
            // extra_field
            $this->add(array(
                'name' => 'extra_field',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('Extra fields'),
                ),
            ));
            foreach ($this->field as $field) {
                if ($field['type'] == 'select') {
                    $this->add(array(
                        'name' => $field['id'],
                        'type' => 'select',
                        'options' => array(
                            'label' => sprintf('%s (%s)', $field['title'], $field['type']),
                            'value_options' => $this->makeArray($field['value']),
                        ),
                    ));
                } else {
                    $this->add(array(
                        'name' => $field['id'],
                        'options' => array(
                            'label' => sprintf('%s (%s)', $field['title'], $field['type']),
                        ),
                        'attributes' => array(
                            'type' => 'text',
                        )
                    ));
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