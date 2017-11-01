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

namespace Module\Shop\Form\Element;

use Pi;
use Zend\Form\Element\Button as ZendButton;

class PropertyColor extends ZendButton
{
    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->Attributes = [
            'class' => 'add_property_color btn btn-success',
        ];
        return $this->Attributes;
    }
} 
