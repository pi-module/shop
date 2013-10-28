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

class PropertyForm  extends BaseForm
{

    public function __construct($name = null, $items = array())
    {
        $this->items = $items;
        parent::__construct($name);
    }

    public function init()
    {
        // Add all items
        foreach ($this->items as $item) {
        	$this->add(array(
            	'name' => $item['name'],
            	'options' => array(
                	'label' => $item['title'],
            	),
            	'attributes' => array(
                	'type' => $item['edit']['type'],
                	'description' => '',
                	'class' => 'span6',
                	'value' => $item['value'],
            	)
        	));
        }
        // Save
        $this->add(array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => __('Submit'),
            )
        ));
    }
}