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

class ProductForm extends BaseForm
{
    protected $thumbUrl = '';

    public function __construct($name = null, $option = [])
    {
        $this->option    = $option;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new ProductFilter($this->option);
        }
        return $this->filter;
    }

    public function init()
    {
        // extra_general
        $this->add(
            [
                'name'    => 'extra_general',
                'type'    => 'fieldset',
                'options' => [
                    'label' => __('General options'),
                ],
            ]
        );

        // title
        $this->add(
            [
                'name'       => 'title',
                'options'    => [
                    'label' => __('Title'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                    'required'    => true,
                ],
            ]
        );

        // subtitle
        $this->add(
            [
                'name'       => 'subtitle',
                'options'    => [
                    'label' => __('Second title'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                ],
            ]
        );

        // slug
        $this->add(
            [
                'name'       => 'slug',
                'options'    => [
                    'label' => __('slug'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                ],
            ]
        );

        // code
        $this->add(
            [
                'name'       => 'code',
                'options'    => [
                    'label' => __('Product code'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => __('Code should be unique or set it empty'),
                ],
            ]
        );

        // text_summary
        $this->add(
            [
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
            ]
        );

        // text_description
        $this->add(
            [
                'name'       => 'text_description',
                'options'    => [
                    'label'  => __('Description'),
                    'editor' => 'html',
                ],
                'attributes' => [
                    'type'        => 'editor',
                    'description' => '',
                ],
            ]
        );

        // status
        $this->add(
            [
                'name'       => 'status',
                'type'       => 'select',
                'options'    => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Published'),
                        2 => __('Pending review'),
                        3 => __('Draft'),
                        4 => __('Private'),
                        5 => __('Delete'),
                    ],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        // category
        $this->add(
            [
                'name'       => 'category',
                'type'       => 'Module\Shop\Form\Element\Category',
                'options'    => [
                    'label'    => __('Category'),
                    'category' => [],
                ],
                'attributes' => [
                    'required' => true,
                ],
            ]
        );

        // category_main
        $this->add(
            [
                'name'       => 'category_main',
                'type'       => 'Module\Shop\Form\Element\Category',
                'options'    => [
                    'label'    => __('Main category'),
                    'category' => [],
                ],
                'attributes' => [
                    'size'        => 1,
                    'multiple'    => 0,
                    'description' => __('Use for breadcrumbs ,mobile app and attribute'),
                    'required'    => true,
                ],
            ]
        );

        // brand
        if ($this->option['brand_system']) {
            $this->add(
                [
                    'name'       => 'brand',
                    'type'       => 'Module\Shop\Form\Element\Brand',
                    'options'    => [
                        'label'    => __('Brand'),
                        'category' => $this->category,
                    ],
                    'attributes' => [
                        'size'     => 1,
                        'multiple' => 0,
                        'required' => false,
                    ],
                ]
            );
        }

        // company_id
        if ($this->option['dashboard_active'] && Pi::service('module')->isActive('company')) {
            $this->add(
                [
                    'name'       => 'company_id',
                    'type'       => 'Module\Company\Form\Element\InventoryList',
                    'options'    => [
                        'label'    => __('Company'),
                    ],
                    'attributes' => [
                        'size'     => 1,
                        'multiple' => 0,
                        'required' => false,
                    ],
                ]
            );
        }

        // main_image
        $this->add(
            [
                'name'    => 'main_image',
                'type'    => 'Module\Media\Form\Element\Media',
                'options' => [
                    'label'  => __('Main image'),
                    'module' => 'shop',
                ],
            ]
        );

        // additional_images
        $this->add(
            [
                'name'    => 'additional_images',
                'type'    => 'Module\Media\Form\Element\Media',
                'options' => [
                    'label'         => __('Additional images'),
                    'media_gallery' => true,
                    'module'        => 'shop',
                ],
            ]
        );

        // extra_seo
        $this->add(
            [
                'name'    => 'extra_seo',
                'type'    => 'fieldset',
                'options' => [
                    'label' => __('SEO options'),
                ],
            ]
        );

        // seo_title
        $this->add(
            [
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
            ]
        );

        // seo_keywords
        $this->add(
            [
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
            ]
        );

        // seo_description
        $this->add(
            [
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
            ]
        );

        // tag
        if (Pi::service('module')->isActive('tag')) {
            $this->add(
                [
                    'name'       => 'tag',
                    'type'       => 'tag',
                    'options'    => [
                        'label' => __('Tags'),
                    ],
                    'attributes' => [
                        'id'          => 'tag',
                        'description' => __('Use `|` as delimiter to separate tag terms'),
                    ],
                ]
            );
        }

        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'class' => 'btn btn-primary',
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}
