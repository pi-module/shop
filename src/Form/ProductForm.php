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

class ProductForm extends BaseForm
{
    protected $thumbUrl = '';

    public function __construct($name = null, $option = array())
    {
        $this->category = array(0 => '');
        $this->thumbUrl = (isset($option['thumbUrl'])) ? $option['thumbUrl'] : '';
        $this->removeUrl = (isset($option['removeUrl'])) ? $option['removeUrl'] : '';
        $this->config = Pi::service('registry')->config->read('shop');
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
                'required' => true,
            )
        ));
        // subtitle
        $this->add(array(
            'name' => 'subtitle',
            'options' => array(
                'label' => __('Second title'),
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
        // text_summary
        $this->add(array(
            'name' => 'text_summary',
            'options' => array(
                'label' => __('Summary'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
                'description' => __('Keep summery short, 2 or 3 lines'),
            )
        ));
        // text_description
        $this->add(array(
            'name' => 'text_description',
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
                    5 => __('Delete'),
                ),
            ),
            'attributes' => array(
                'required' => true,
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
            'attributes' => array(
                'required' => true,
            )
        ));
        // category_main
        $this->add(array(
            'name' => 'category_main',
            'type' => 'Module\Shop\Form\Element\Category',
            'options' => array(
                'label' => __('Main category'),
                'category' => $this->category,
            ),
            'attributes' => array(
                'size' => 1,
                'multiple' => 0,
                'description' => __('Use for breadcrumbs ,mobile app and attribute'),
                'required' => true,
            ),
        ));
        // Image
        if ($this->thumbUrl) {
            $this->add(array(
                'name' => 'imageview',
                'type' => 'Module\Shop\Form\Element\Image',
                'options' => array(//'label' => __('Image'),
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
        if ($this->config['order_stock'] == 'product') {
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
        } else {
            $this->add(array(
                'name' => 'stock',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // stock_type
        if ($this->config['order_stock'] == 'manual') {
            $this->add(array(
                'name' => 'stock_type',
                'type' => 'select',
                'options' => array(
                    'label' => __('Stock type'),
                    'value_options' => array(
                        1 => __('In stock'),
                        2 => __('Out of stock'),
                        3 => __('Coming soon'),
                        4 => __('Contact'),
                        5 => __('Variable stock'),
                    ),
                ),
                'attributes' => array(
                    'required' => true,
                )
            ));
        } else {
            $this->add(array(
                'name' => 'stock_type',
                'attributes' => array(
                    'type' => 'hidden',
                ),
            ));
        }
        // price
        $this->add(array(
            'name' => 'price',
            'options' => array(
                'label' => __('Price'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Real price'),

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
                'description' => __('Display prices'),

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
        // order_discount
        if ($this->config['order_discount']) {
            // extra_product
            $this->add(array(
                'name' => 'extra_discount',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('Discount options'),
                ),
            ));
            // Get role list
            $roles = Pi::service('registry')->Role->read('front');
            unset($roles['webmaster']);
            unset($roles['guest']);
            foreach ($roles as $name => $role) {
                $this->add(array(
                    'name' => $name,
                    'options' => array(
                        'label' => $role['title'],
                    ),
                    'attributes' => array(
                        'type' => 'text',
                        'description' => __('Number and between 1 to 99'),
                    )
                ));
            }
        }
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
                'type' => 'textarea',
                'rows' => '2',
                'cols' => '40',
                'description' => __('Between 10 to 70 character'),
            )
        ));
        // seo_keywords
        $this->add(array(
            'name' => 'seo_keywords',
            'options' => array(
                'label' => __('SEO Keywords'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '2',
                'cols' => '40',
                'description' => __('Between 5 to 10 words'),
            )
        ));
        // seo_description
        $this->add(array(
            'name' => 'seo_description',
            'options' => array(
                'label' => __('SEO Description'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '3',
                'cols' => '40',
                'description' => __('Between 80 to 160 character'),
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
                    'id' => 'tag',
                    'description' => __('Use `|` as delimiter to separate tag terms'),
                )
            ));
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
}