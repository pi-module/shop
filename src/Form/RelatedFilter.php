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
use Zend\InputFilter\InputFilter;

class RelatedFilter extends InputFilter
{
    public function __construct()
    {
        // title
        $this->add(
            [
                'name'     => 'title',
                'required' => false,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // type
        $this->add(
            [
                'name'     => 'type',
                'required' => false,
            ]
        );
        // category
        $this->add(
            [
                'name'     => 'category',
                'required' => false,
            ]
        );
    }
}  