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
 * Pi::api('attribute', 'shop')->Get();
 * Pi::api('attribute', 'shop')->Set($attribute, $product);
 * Pi::api('attribute', 'shop')->Form($values);
 * Pi::api('attribute', 'shop')->Product($id);
 * Pi::api('attribute', 'shop')->SearchForm($form);
 * Pi::api('attribute', 'shop')->findFromattribute($search);
 */

class Attribute extends AbstractApi
{
    /*
      * Get list of attribute fields for show in forms
      */
    public function Get()
    {
        $return = array(
            'attribute' => '',
            'field' => '',
        );
        $whereField = array('status' => 1);
        $orderField = array('order DESC', 'id DESC');
        $select = Pi::model('field', $this->getModule())->select()->where($whereField)->order($orderField);
        $rowset = Pi::model('field', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $return['attribute'][$row->id] = $row->toArray();
            $return['field'][$row->id] = $return['attribute'][$row->id]['id'];
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
    public function Product($id)
    {
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
            $whereField = array('status' => 1);
            $columnField = array('id', 'title', 'image', 'type');
            $orderField = array('order ASC', 'id ASC');
            $select = Pi::model('field', $this->getModule())->select()->where($whereField)->columns($columnField)->order($orderField);
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
                        $field['all'][$row->id] = $row->toArray();
                        $field['all'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        if ($field['all'][$row->id]['image']) {
                            $field['all'][$row->id]['imageUrl'] = Pi::url('upload/' . $this->getModule() . '/attribute/' . $field['all'][$row->id]['image']);
                        }
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
}