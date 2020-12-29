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

class Remove extends LaminasButton
{
    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->Attributes = [
            'class'       => 'img-remove btn btn-danger btn-sm',
            'data-toggle' => 'button',
            'data-link'   => $this->attributes['link'],
        ];
        return $this->Attributes;
    }
}
