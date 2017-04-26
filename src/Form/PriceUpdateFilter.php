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

class PriceUpdateFilter extends InputFilter
{
    public function __construct()
    {
        // category
        $this->add(array(
            'name' => 'category',
            'required' => true,
            'validators' => array(
                new \Module\Shop\Validator\Category,
            ),
        ));
        // percent
        $this->add(array(
            'name' => 'percent',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim',
                ),
            ),
            'validators' => array(
                new \Module\Shop\Validator\Percent,
            ),
        ));
    }
}