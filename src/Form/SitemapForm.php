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
use Pi\Form\Form as BaseForm;

class SitemapForm extends BaseForm
{
    public function __construct($name = null)
    {
        parent::__construct($name);
    }

    public function init()
    {
        // type
        $this->add([
            'name'       => 'type',
            'type'       => 'select',
            'options'    => [
                'label'         => __('Select for rebuild'),
                'value_options' => [
                    1 => __('All of tables'),
                    2 => __('Just product table'),
                    3 => __('Just category table'),
                ],
            ],
            'attributes' => [
                'required' => true,
            ],
        ]);
        // Save
        $this->add([
            'name'       => 'submit',
            'type'       => 'submit',
            'attributes' => [
                'value' => __('Submit'),
            ],
        ]);
    }
}	