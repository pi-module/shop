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

class CategoryMergeFilter extends InputFilter
{
    public function __construct($option)
    {
        // category_from_1
        $this->add(array(
            'name' => 'category_from_1',
            'required' => true,
            'validators' => array(
                new \Module\Shop\Validator\Category,
            ),
        ));
        // category_from_2
        $this->add(array(
            'name' => 'category_from_2',
            'required' => true,
            'validators' => array(
                new \Module\Shop\Validator\Category,
            ),
        ));
        // where_type
        $this->add(array(
            'name' => 'where_type',
            'required' => true,
        ));
        // category_to
        $this->add(array(
            'name' => 'category_to',
            'required' => true,
            'validators' => array(
                new \Module\Shop\Validator\Category,
            ),
        ));
    }
}