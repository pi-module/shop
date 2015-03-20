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

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('attribute', 'shop')->Get($category);
 * Pi::api('attribute', 'shop')->Set($attribute, $product);
 * Pi::api('attribute', 'shop')->Form($values);
 * Pi::api('attribute', 'shop')->Product($id);
 * Pi::api('attribute', 'shop')->SearchForm($form);
 * Pi::api('attribute', 'shop')->findFromattribute($search);
 * Pi::api('attribute', 'shop')->setCategory($field, $categoryArr);
 * Pi::api('attribute', 'shop')->getCategory($field);
 * Pi::api('attribute', 'shop')->getField($business);
 * Pi::api('attribute', 'shop')->attributePositionForm();
 */

class Attribute extends AbstractApi
{
    /*
      * Get list of attribute fields for show in forms
      */
    public function Get($category)
    {
        // Set return
        $return = array(
            'attribute' => '',
            'field' => '',
        );
        // Get position list
        $position = $this->attributePositionForm();
        // Get field id from business
        $id = $this->getField($category);
        if (empty($id)) {
            return $return;
        }
        // find
        $whereField = array('status' => 1, 'id' => $id);
        $orderField = array('order DESC', 'position ASC', 'id DESC');
        $select = Pi::model('field', $this->getModule())->select()->where($whereField)->order($orderField);
        $rowset = Pi::model('field', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $return['attribute'][$row->position][$row->id] = $row->toArray();
            switch ($row->type) {
                case 'text':
                    $type_vew = __('Text');
                    break;

                case 'link':
                    $type_vew = __('Link');
                    break;  

                case 'video':
                    $type_vew = __('Video');
                    break;

                case 'audio':
                    $type_vew = __('Audio');
                    break;
                    
                case 'file':
                    $type_vew = __('File');
                    break;    

                case 'currency':
                    $type_vew = __('Currency');
                    break;

                case 'date':
                    $type_vew = __('Date');
                    break;  

                case 'number':
                    $type_vew = __('Number');
                    break;

                case 'select':
                    $type_vew = __('Select');
                    break;
                    
                case 'checkbox':
                    $type_vew = __('Checkbox');
                    break; 
            }
            $return['attribute'][$row->position][$row->id]['type_vew'] = $type_vew;
            $return['attribute'][$row->position][$row->id]['position_vew'] = $position[$row->position];
            $return['field'][$row->id] = $return['attribute'][$row->position][$row->id]['id'];
        }
        return $return;
    }

    /*
      * Save attribute field datas to DB
      */
    public function Set($attribute, $product)
    {
        foreach ($attribute as $field) {
            // Find row
            $where = array('field' => $field['field'], 'product' => $product);
            $select = Pi::model('field_data', $this->getModule())->select()->where($where)->limit(1);
            $row = Pi::model('field_data', $this->getModule())->selectWith($select)->current();
            // create new row
            if (empty($row)) {
                $row = Pi::model('field_data', $this->getModule())->createRow();
                $row->field = $field['field'];
                $row->product = $product;
            }
            // Save or delete row
            if (empty($field['data'])) {
                $row->delete();
            } else {
                $row->data = $field['data'];
                $row->save();
            }
        }
        // Set Product attribute Count
        Pi::api('product', 'shop')->attributeCount($product);
    }

    /*
      * Get and Set attribute field data valuse to form
      */
    public function Form($values)
    {
        $where = array('product' => $values['id']);
        $select = Pi::model('field_data', $this->getModule())->select()->where($where);
        $rowset = Pi::model('field_data', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $field[$row->field] = $row->toArray();
            $values[$field[$row->field]['field']] = $field[$row->field]['data'];
        }
        return $values;
    }

    /*
      * Get all attribute field data for selected Product
      */
    public function Product($id, $category)
    {
        $position = $this->attributePositionForm();
        // Get data list
        $whereData = array('product' => $id);
        $columnData = array('field', 'data');
        $select = Pi::model('field_data', $this->getModule())->select()->where($whereData)->columns($columnData);
        $rowset = Pi::model('field_data', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $data[$row->field] = $row->toArray();
        }
        // Get field list
        $field = array();
        if (!empty($data)) {
            // Get field id from category
            $id = $this->getField($category);
            if (empty($id)) {
                return array();
            }
            // Select
            $whereField = array('status' => 1, 'id' => $id);
            $orderField = array('order ASC', 'id ASC');
            $select = Pi::model('field', $this->getModule())->select()->where($whereField)->order($orderField);
            $rowset = Pi::model('field', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                switch ($row->type) {
                    case 'audio':
                        $field['audio'][$row->id] = $row->toArray();
                        $field['audio'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        if ($field['audio'][$row->id]['image']) {
                            $field['audio'][$row->id]['imageUrl'] = Pi::url('upload/' . $this->getModule() . '/attribute/' . $field['audio'][$row->id]['image']);
                        }
                        break;

                    case 'video':
                        $field['video'][$row->id] = $row->toArray();
                        $field['video'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        if ($field['video'][$row->id]['image']) {
                            $field['video'][$row->id]['imageUrl'] = Pi::url('upload/' . $this->getModule() . '/attribute/' . $field['video'][$row->id]['image']);
                        }
                        break;
                    
                    default:
                        $field['all'][$row->position]['info'][$row->id] = $row->toArray();
                        $field['all'][$row->position]['info'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        if ($field['all'][$row->position]['info'][$row->id]['image']) {
                            $field['all'][$row->position]['info'][$row->id]['imageUrl'] = Pi::url(
                                sprintf('upload/%s/attribute/%s', 
                                $this->getModule(), 
                                $field['all'][$row->position]['info'][$row->id]['image']
                            ));
                        }
                        $field['all'][$row->position]['title'] = $position[$row->position];
                        break;
                }             
            }
        }
        // return
        return $field;
    }

    /*
      * Set attribute filds from search form
      */
    public function SearchForm($form)
    {
        $attribute = array();
        // unset other field
        unset($form['type']);
        unset($form['title']);
        unset($form['price_from']);
        unset($form['price_to']);
        unset($form['category']);
        // Make list
        foreach ($form as $key => $value) {
            if (is_numeric($key) && !empty($value)) {
                $item = array();
                $item['field'] = $key;
                $item['data'] = $value;
                $attribute[$key] = $item;
            }
        }
        return $attribute;
    }

    /*
      * Set attribute filds from search form
      */
    public function findFromAttribute($search)
    {
        $id = array();
        $column = array('product');
        foreach ($search as $attribute) {
            $where = array(
                'field' => $attribute['field'], 
                'data' => $attribute['data'],
            );
            $select = Pi::model('field_data', $this->getModule())->select()->where($where)->columns($column);
            $rowset = Pi::model('field_data', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                if (isset($row->product) && !empty($row->product)) {
                    $id[] = $row->product;
                }
            }
        }
        $id = array_unique($id);
        return $id;
    }

    public function attributePositionForm()
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Set position array
        $position = array(
            1 => $config['attribute_position_1'],
            2 => $config['attribute_position_2'],
            3 => $config['attribute_position_3'],
            4 => $config['attribute_position_4'],
            5 => $config['attribute_position_5'],
            6 => $config['attribute_position_6'],
            7 => $config['attribute_position_7'],
            8 => $config['attribute_position_8'],
            9 => $config['attribute_position_9'],
            10 => $config['attribute_position_10'],
        );
        return $position;
    }

    public function setCategory($field, $categoryArr)
    {
        // Remove
        Pi::model('field_category', $this->getModule())->delete(array('field' => $field));
        // Add
        foreach ($categoryArr as $category) {
            // Save
            $row = Pi::model('field_category', $this->getModule())->createRow();
            $row->field = $field;
            $row->category = $category;
            $row->save();
        }
    }

    public function getCategory($field)
    {
        $category = array();
        $where = array('field' => $field);
        $select = Pi::model('field_category', $this->getModule())->select()->where($where);
        $rowset = Pi::model('field_category', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $category[] = $row->category;
        }
        return array_unique($category);
    }

    public function getField($category)
    {
        $field = array();
        $where = array('category' => $category);
        $select = Pi::model('field_category', $this->getModule())->select()->where($where);
        $rowset = Pi::model('field_category', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $field[] = $row->field;
        }
        return array_unique($field);
    }
}