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
use Zend\InputFilter\InputFilter;

class CategoryFilter extends InputFilter
{
    public function __construct($option)
    {
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // parent
        $this->add(array(
            'name' => 'parent',
            'required' => false,
        ));
        // title
        $this->add(array(
            'name' => 'title',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // slug
        $this->add(array(
            'name' => 'slug',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Shop\Validator\SlugDuplicate(array(
                    'module' => Pi::service('module')->current(),
                    'table' => 'category',
                )),
            ),
        ));
        // text_summary
        $this->add(array(
            'name' => 'text_summary',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // text_description
        $this->add(array(
            'name' => 'text_description',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // display_order
        $this->add(array(
            'name' => 'display_order',
            'required' => false,
        ));
        // display_type
        $this->add(array(
            'name' => 'display_type',
            'required' => false,
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'required' => true,
        ));
        // image
        $this->add(array(
            'name' => 'image',
            'required' => false,
        ));
        // image_wide
        $this->add(array(
            'name' => 'image_wide',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // Check is new
        if ($option['isNew']) {
            // percent
            $this->add(array(
                'name' => 'sale_percent',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
            ));
            // time_publish
            $this->add(array(
                'name' => 'sale_time_publish',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
                'validators' => array(
                    new \Module\Shop\Validator\TimeSelect,
                ),
            ));
            // time_expire
            $this->add(array(
                'name' => 'sale_time_expire',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
                'validators' => array(
                    new \Module\Shop\Validator\TimeSelect,
                ),
            ));
        }
        // seo_title
        $this->add(array(
            'name' => 'seo_title',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // seo_keywords
        $this->add(array(
            'name' => 'seo_keywords',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // seo_description
        $this->add(array(
            'name' => 'seo_description',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
    }
}    	