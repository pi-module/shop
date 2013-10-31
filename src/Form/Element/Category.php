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

class Category extends Select
{

    /**
     * @return array
     */
    public function getValueOptions()
    {
        if (empty($this->valueOptions)) {
            // Get topic list
            $select = Pi::model('category', 'shop')->select()->columns(array('id', 'parent', 'title'));
            $rowset = Pi::model('category', 'shop')->selectWith($select);
            foreach ($rowset as $row) {
                $list[$row->id] = $row->toArray();
            }
            $this->valueOptions = $this->getTree($list);
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

    public function getTree($elements, $parentId = 0)
    {
        $branch = array();
        // Set default category options
        if ($parentId == 0) {
            if (!isset($this->options['category'])) {
                $branch[0] = __('All Category');
            } else {
                $branch = $this->options['category'];
            }
        }
        // Set category list as tree
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $depth = 0;
                $branch[$element['id']] = $element['title'];
                $children = $this->getTree($elements, $element['id']);
                if ($children) {
                    $depth++;
                    foreach ($children as $key => $value) {
                        $branch[$key] = sprintf('%s%s' , str_repeat('-', $depth), $value);
                    }
                }
                unset($elements[$element['id']]);
                unset($depth);
            }
        }
        return $branch;
    }
}