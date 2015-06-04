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

class PropertyFilter extends InputFilter
{
    public function __construct()
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
        // order
        $this->add(array(
            'name' => 'order',
            'required' => false,
        ));
        // status
        $this->add(array(
            'name' => 'status',
            'required' => false,
        ));
        // influence_stock
        $this->add(array(
            'name'          => 'influence_stock',
            'required'      => false,
        ));
        // influence_price
        $this->add(array(
            'name'          => 'influence_price',
            'required'      => false,
        ));
        // type
        $this->add(array(
            'name'          => 'type',
            'required'      => false,
        ));
    }
}