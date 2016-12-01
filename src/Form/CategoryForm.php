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

class CategoryForm extends BaseForm
{
    protected $thumbUrl = '';

    public function __construct($name = null, $option = array())
    {
        $this->option = $option;
        $this->category = array(0 => '');
        $this->thumbUrl = $option['thumbUrl'];
        $this->removeUrl = empty($option['removeUrl']) ? '' : $option['removeUrl'];
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new CategoryFilter($this->option);
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
        // parent
        $this->add(array(
            'name' => 'parent',
            'type' => 'Module\Shop\Form\Element\Category',
            'options' => array(
                'label' => __('Parent Category'),
                'category' => $this->category,
            ),
            'attributes' => array(
                'size' => 1,
                'multiple' => 0,
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
        // display_order
        $this->add(array(
            'name' => 'display_order',
            'options' => array(
                'label' => __('Display order'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => '',
                'required' => false,
            )
        ));
        // display_type
        $this->add(array(
            'name' => 'display_type',
            'type' => 'select',
            'options' => array(
                'label' => __('Display type'),
                'value_options' => array(
                    'product' => __('List of products'),
                    'subcategory' => __('List of sub category'),
                ),
            ),
            'attributes' => array(
                'required' => true,
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
            'attributes' => array(
                'required' => true,
            )
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
        // image_wide
        $this->add(array(
            'name' => 'image_wide',
            'options' => array(
                'label' => __('Wide image'),
            ),
            'attributes' => array(
                'type' => 'text',
                'description' => __('Set wide image url for category page header'),

            )
        ));
        // Check is new
        if ($this->option['isNew']) {
            // extra
            $this->add(array(
                'name' => 'extra_sale',
                'type' => 'fieldset',
                'options' => array(
                    'label' => __('Sale options'),
                ),
            ));
            // sale_percent
            $this->add(array(
                'name' => 'sale_percent',
                'options' => array(
                    'label' => __('Percent'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => __('Discount percent'),
                )
            ));
            // sale_time_publish
            $this->add(array(
                'name' => 'sale_time_publish',
                'option' => array(
                    'label' => __('Sale publish time'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                )
            ));
            // sale_time_expire
            $this->add(array(
                'name' => 'sale_time_expire',
                'option' => array(
                    'label' => __('Sale expire time'),
                ),
                'attributes' => array(
                    'type' => 'text',
                    'description' => '',
                )
            ));
        }
        // extra
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