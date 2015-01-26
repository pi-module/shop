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

class ProductFilter extends InputFilter
{
    public function __construct($attribute = null)
    {
        // id
        $this->add(array(
            'name' => 'id',
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
            'name'          => 'slug',
            'required'      => false,
            'filters'       => array(
                array(
                    'name'  => 'StringTrim',
                ),
            ),
            'validators'    => array(
                new \Module\Shop\Validator\SlugDuplicate(array(
                    'module'            => Pi::service('module')->current(),
                    'table'             => 'product',
                )),
            ),
        ));
        // text_summary
        $this->add(array(
            'name' => 'summary',
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
        // status
        $this->add(array(
            'name' => 'status',
            'required' => true,
        ));
        // category
        $this->add(array(
            'name' => 'category',
            'required' => true,
        ));
        // brand
        $this->add(array(
            'name' => 'brand',
            'required' => true,
        ));
        // image
        $this->add(array(
            'name' => 'image',
            'required' => false,
        ));
        // stock
        $this->add(array(
            'name' => 'stock',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // stock_alert
        $this->add(array(
            'name' => 'stock_alert',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // stock_type
        $this->add(array(
            'name' => 'stock_type',
            'required' => true,
        ));
        // price
        $this->add(array(
            'name' => 'price',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // price_title
        $this->add(array(
            'name' => 'price_title',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // price_discount
        $this->add(array(
            'name' => 'price_discount',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
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
        // tag
        if (Pi::service('module')->isActive('tag')) {
            $this->add(array(
                'name' => 'tag',
                'required' => false,
            ));
        }
        // Set attribute field
        if (!empty($attribute)) {
            foreach ($attribute as $field) {
                $this->add(array(
                    'name' => $field['id'],
                    'required' => false,
                ));
            }
        }
    }
}    	