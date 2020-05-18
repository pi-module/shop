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

namespace Module\Shop\Form\Element;

use Pi;
use Laminas\Form\Element\Button as LaminasButton;

class Property extends LaminasButton
{
    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->Attributes = [
            'class' => sprintf('add_property_%s btn btn-success', $this->attributes['id']),
        ];
        return $this->Attributes;
    }
} 
