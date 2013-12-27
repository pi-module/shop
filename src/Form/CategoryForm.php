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

class CategoryForm  extends BaseForm
{
    protected $thumbUrl = '';
    
    public function __construct($name = null, $option = array())
    {
        $this->category = array(0 => 'Root');
        $this->thumbUrl = $option['thumbUrl'];
        $this->removeUrl = empty($option['removeUrl']) ? '' : $option['removeUrl'];
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new CategoryFilter;
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
        // description
        $this->add(array(
            'name' => 'description',
            'options' => array(
                'label' => __('Description'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
                
                'description' => '',
            )
        ));
        // description_footer
        $this->add(array(
            'name' => 'description_footer',
            'options' => array(
                'label' => __('Footer description'),
            ),
            'attributes' => array(
                'type' => 'textarea',
                'rows' => '5',
                'cols' => '40',
                
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
                    'class' => 'btn btn-danger btn-sm',
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