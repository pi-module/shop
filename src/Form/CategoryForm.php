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

    public function __construct($name = null, $option = [])
    {
        $this->option = $option;
        $this->category = [0 => ''];
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
        $this->add([
            'name'       => 'id',
            'attributes' => [
                'type' => 'hidden',
            ],
        ]);
        // parent
        if ($this->option['type'] == 'category') {
            $this->add([
                'name'       => 'parent',
                'type'       => 'Module\Shop\Form\Element\Company',
                'options'    => [
                    'label'    => __('Parent Category'),
                    'category' => $this->category,
                ],
                'attributes' => [
                    'size'     => 1,
                    'multiple' => 0,
                ],
            ]);
        }
        // title
        $this->add([
            'name'       => 'title',
            'options'    => [
                'label' => __('Title'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',
                'required'    => true,
            ],
        ]);
        // slug
        $this->add([
            'name'       => 'slug',
            'options'    => [
                'label' => __('slug'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',

            ],
        ]);
        // text_summary
        $this->add([
            'name'       => 'text_summary',
            'options'    => [
                'label' => __('Summary'),
            ],
            'attributes' => [
                'type'        => 'textarea',
                'rows'        => '5',
                'cols'        => '40',
                'description' => __('Keep summery short, 2 or 3 lines'),
            ],
        ]);
        // text_description
        $this->add([
            'name'       => 'text_description',
            'options'    => [
                'label'  => __('Description'),
                'editor' => 'html',
            ],
            'attributes' => [
                'type'        => 'editor',
                'description' => '',
            ],
        ]);
        // type
        $this->add([
            'name'       => 'type',
            'type'       => 'select',
            'options'    => [
                'label'         => __('Category type'),
                'value_options' => [
                    'category' => __('Category'),
                    'brand'    => __('Brand'),
                ],
            ],
            'attributes' => [
                'required' => true,
                'value'    => $this->option['type'],
            ],
        ]);
        // display_order
        $this->add([
            'name'       => 'display_order',
            'options'    => [
                'label' => __('Display order'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => '',
                'required'    => false,
            ],
        ]);
        // display_type
        $this->add([
            'name'       => 'display_type',
            'type'       => 'select',
            'options'    => [
                'label'         => __('Display type'),
                'value_options' => [
                    'product'     => __('List of products'),
                    'subcategory' => __('List of sub category'),
                ],
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);
        // status
        $this->add([
            'name'       => 'status',
            'type'       => 'select',
            'options'    => [
                'label'         => __('Status'),
                'value_options' => [
                    1 => __('Published'),
                    2 => __('Pending review'),
                    3 => __('Draft'),
                    4 => __('Private'),
                ],
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);
        // Image
        if ($this->thumbUrl) {
            $this->add([
                'name'       => 'imageview',
                'type'       => 'Module\Shop\Form\Element\Image',
                'options'    => [//'label' => __('Image'),
                ],
                'attributes' => [
                    'src' => $this->thumbUrl,
                ],
            ]);
            $this->add([
                'name'       => 'remove',
                'type'       => 'Module\Shop\Form\Element\Remove',
                'options'    => [
                    'label' => __('Remove image'),
                ],
                'attributes' => [
                    'link' => $this->removeUrl,
                ],
            ]);
            $this->add([
                'name'       => 'image',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]);
        } else {
            $this->add([
                'name'       => 'image',
                'options'    => [
                    'label' => __('Image'),
                ],
                'attributes' => [
                    'type'        => 'file',
                    'description' => '',
                ],
            ]);
        }
        // image_wide
        $this->add([
            'name'       => 'image_wide',
            'options'    => [
                'label' => __('Wide image'),
            ],
            'attributes' => [
                'type'        => 'text',
                'description' => __('Set wide image url for category page header'),

            ],
        ]);
        // Check is new
        if ($this->option['isNew']) {
            // extra
            $this->add([
                'name'    => 'extra_sale',
                'type'    => 'fieldset',
                'options' => [
                    'label' => __('Sale options'),
                ],
            ]);
            // sale_percent
            $this->add([
                'name'       => 'sale_percent',
                'options'    => [
                    'label' => __('Percent'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Discount percent'),
                ],
            ]);
            // sale_time_publish
            $this->add([
                'name'       => 'sale_time_publish',
                'option'     => [
                    'label' => __('Sale publish time'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                ],
            ]);
            // sale_time_expire
            $this->add([
                'name'       => 'sale_time_expire',
                'option'     => [
                    'label' => __('Sale expire time'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                ],
            ]);
        }
        // extra
        $this->add([
            'name'    => 'extra_seo',
            'type'    => 'fieldset',
            'options' => [
                'label' => __('SEO options'),
            ],
        ]);
        // seo_title
        $this->add([
            'name'       => 'seo_title',
            'options'    => [
                'label' => __('SEO Title'),
            ],
            'attributes' => [
                'type'        => 'textarea',
                'rows'        => '2',
                'cols'        => '40',
                'description' => __('Between 10 to 70 character'),
            ],
        ]);
        // seo_keywords
        $this->add([
            'name'       => 'seo_keywords',
            'options'    => [
                'label' => __('SEO Keywords'),
            ],
            'attributes' => [
                'type'        => 'textarea',
                'rows'        => '2',
                'cols'        => '40',
                'description' => __('Between 5 to 10 words'),
            ],
        ]);
        // seo_description
        $this->add([
            'name'       => 'seo_description',
            'options'    => [
                'label' => __('SEO Description'),
            ],
            'attributes' => [
                'type'        => 'textarea',
                'rows'        => '3',
                'cols'        => '40',
                'description' => __('Between 80 to 160 character'),
            ],
        ]);
        // Save
        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => __('Submit'),
            ],
        ]);
    }
}