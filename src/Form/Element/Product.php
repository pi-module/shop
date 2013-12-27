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
use Zend\Form\Element\Select;

class Product extends Select
{

    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            if (isset($this->options['product'])) {
                $options = $this->options['product'];
            }
            $limit = (isset($this->options['limit'])) ? $this->options['limit'] : 50;
            $columns = array('id', 'title');
            $order = array('time_create DESC', 'id DESC');
            $select = Pi::model('product', 'shop')->select()->columns($columns)->order($order)->limit($limit);
            $rowset = Pi::model('product', 'shop')->selectWith($select);
            foreach ($rowset as $row) {
                $list[$row->id] = $row->toArray();
                $options[$row->id] = $list[$row->id]['title'];
            }
            $this->valueOptions = $options;
        }
        return $this->valueOptions;
    }

    /**
     * @return array
     */
    public function getAttributes()
    {
        $this->Attributes = array(
            'size' => 5,
            'multiple' => 1,
            'class' => 'form-control',
        );
        // check form size
        if (isset($this->attributes['size'])) {
            $this->Attributes['size'] = $this->attributes['size'];
        }
        // check form multiple
        if (isset($this->attributes['multiple'])) {
            $this->Attributes['multiple'] = $this->attributes['multiple'];
        }
        return $this->Attributes;
    }
}