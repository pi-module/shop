<?php
/**
 * Attach form
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       Copyright (c) Pi Engine http://www.xoopsengine.org
 * @license         http://www.xoopsengine.org/license New BSD License
 * @author          Hossein Azizabadi <azizabadi@faragostaresh.com>
 * @since           3.0
 * @package         Module\News
 * @version         $Id$
 */

namespace Module\Shop\Form;

use Pi;
use Pi\Form\Form as BaseForm;

class AttachForm extends BaseForm
{

    public function __construct($name = null, $product)
    {
        $this->product = $product;
        parent::__construct($name);
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->filter = new AttachFilter;
        }
        return $this->filter;
    }

    public function init()
    {
        // id
        $this->add(
            [
                'name'       => 'id',
                'attributes' => [
                    'type' => 'hidden',
                ],
            ]
        );
        // title
        $this->add(
            [
                'name'       => 'title',
                'options'    => [
                    'label' => __('Title'),
                ],
                'attributes' => [
                    'type'        => 'text',
                    'description' => '',
                ],
            ]
        );
        // story
        $this->add(
            [
                'name'       => 'story',
                'type'       => 'Module\Shop\Form\Element\Product',
                'options'    => [
                    'label' => __('Product'),
                    'story' => $this->product,
                ],
                'attributes' => [
                    'size'     => 1,
                    'multiple' => 0,
                ],

            ]
        );
        // status
        $this->add(
            [
                'name'    => 'status',
                'type'    => 'select',
                'options' => [
                    'label'         => __('Status'),
                    'value_options' => [
                        1 => __('Online'),
                        0 => __('Offline'),
                    ],
                ],
            ]
        );
        // Save
        $this->add(
            [
                'name'       => 'submit',
                'type'       => 'submit',
                'attributes' => [
                    'class' => 'btn btn-primary',
                    'value' => __('Submit'),
                ],
            ]
        );
    }
}