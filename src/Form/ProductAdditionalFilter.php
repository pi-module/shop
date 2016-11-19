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

class ProductAdditionalFilter extends InputFilter
{
    public function __construct($option = array())
    {
        // Get config
        $config = Pi::service('registry')->config->read('shop');
        // Set attribute position
        $position = Pi::api('attribute', 'shop')->attributePositionForm();
        // id
        $this->add(array(
            'name' => 'id',
            'required' => false,
        ));
        // ribbon
        $this->add(array(
            'name' => 'ribbon',
            'required' => false,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
        ));
        // stock
        $this->add(array(
            'name' => 'stock',
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
            'required' => false,
        ));
        // price
        $this->add(array(
            'name' => 'price',
            'required' => true,
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
        // price_shipping
        $this->add(array(
            'name' => 'price_shipping',
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
        // order_discount
        if ($config['order_discount']) {
            // Get role list
            $roles = Pi::service('registry')->Role->read('front');
            unset($roles['webmaster']);
            unset($roles['guest']);
            foreach ($roles as $name => $role) {
                $this->add(array(
                    'name' => $name,
                    'required' => false,
                    'filters' => array(
                        array(
                            'name' => 'StringTrim',
                        ),
                    ),
                ));
            }
        }
        // Set attribute
        if (!empty($option['field'])) {
            foreach ($position as $key => $value) {
                if (!empty($option['field'][$key])) {
                    foreach ($option['field'][$key] as $field) {
                        $this->add(array(
                            'name' => $field['id'],
                            'required' => false,
                        ));
                    }
                }
            }
        }
        // Set video service
        if ($option['video_service'] && Pi::service('module')->isActive('video')) {
            $this->add(array(
                'name' => 'video_list',
                'required' => false,
                'filters' => array(
                    array(
                        'name' => 'StringTrim',
                    ),
                ),
            ));
        }
    }
}