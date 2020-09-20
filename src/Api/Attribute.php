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

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

/*
 * Pi::api('attribute', 'shop')->Get($category);
 * Pi::api('attribute', 'shop')->Set($attribute, $product);
 * Pi::api('attribute', 'shop')->Form($values);
 * Pi::api('attribute', 'shop')->Product($id, $category);
 * Pi::api('attribute', 'shop')->SearchForm($form);
 * Pi::api('attribute', 'shop')->findFromAttribute($search);
 * Pi::api('attribute', 'shop')->setCategory($field, $categoryArr);
 * Pi::api('attribute', 'shop')->getCategory($field);
 * Pi::api('attribute', 'shop')->getField($business);
 * Pi::api('attribute', 'shop')->attributePositionForm();
 * Pi::api('attribute', 'shop')->filterList($category = '');
 * Pi::api('attribute', 'shop')->filterData($productId, $category = '');
 */

class Attribute extends AbstractApi
{
    /*
      * Get list of attribute fields for show in forms
      */
    public function Get($category = '')
    {
        // Set return
        $return = [
            'attribute' => [],
            'field'     => [],
        ];
        // Get position list
        $position = $this->attributePositionForm();
        // Get field id from business
        $id = $this->getField($category);
        if (empty($id)) {
            return $return;
        }
        // find
        $whereField = ['status' => 1, 'id' => $id];
        $orderField = ['order ASC', 'position ASC', 'id DESC'];
        $select     = Pi::model('field', $this->getModule())->select()->where($whereField)->order($orderField);
        $rowSet     = Pi::model('field', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
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
            $return['attribute'][$row->position][$row->id]['type_vew']     = $type_vew;
            $return['attribute'][$row->position][$row->id]['position_vew'] = $position[$row->position];
            $return['field'][$row->id]                                     = $return['attribute'][$row->position][$row->id]['id'];
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
            $where  = ['field' => $field['field'], 'product' => $product];
            $select = Pi::model('field_data', $this->getModule())->select()->where($where)->limit(1);
            $row    = Pi::model('field_data', $this->getModule())->selectWith($select)->current();
            // create new row
            if (empty($row)) {
                $row          = Pi::model('field_data', $this->getModule())->createRow();
                $row->field   = $field['field'];
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
        $where  = ['product' => $values['id']];
        $select = Pi::model('field_data', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('field_data', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $field[$row->field]                   = $row->toArray();
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
        $whereData  = ['product' => $id];
        $columnData = ['field', 'data'];
        $select     = Pi::model('field_data', $this->getModule())->select()->where($whereData)->columns($columnData);
        $rowSet     = Pi::model('field_data', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $data[$row->field] = $row->toArray();
        }
        // Get field list
        $field = [];
        if (!empty($data)) {
            // Get field id from category
            $id = $this->getField($category);
            if (empty($id)) {
                return [];
            }
            // Select
            $whereField = ['status' => 1, 'id' => $id];
            $orderField = ['order ASC', 'id ASC'];
            $select     = Pi::model('field', $this->getModule())->select()->where($whereField)->order($orderField);
            $rowSet     = Pi::model('field', $this->getModule())->selectWith($select);
            foreach ($rowSet as $row) {
                switch ($row->type) {
                    case 'audio':
                        $field['audio'][$row->id]         = $row->toArray();
                        $field['audio'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        break;

                    case 'video':
                        $field['video'][$row->id]         = $row->toArray();
                        $field['video'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        break;

                    default:
                        $field['all'][$row->position]['info'][$row->id]         = $row->toArray();
                        $field['all'][$row->position]['info'][$row->id]['data'] = isset($data[$row->id]['data']) ? $data[$row->id]['data'] : '';
                        $field['all'][$row->position]['title']                  = $position[$row->position];
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
        $attribute = [];
        // unset other field
        unset($form['type']);
        unset($form['title']);
        unset($form['price_from']);
        unset($form['price_to']);
        unset($form['category']);
        // Make list
        foreach ($form as $key => $value) {
            if (is_numeric($key) && !empty($value)) {
                $item            = [];
                $item['field']   = $key;
                $item['data']    = $value;
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
        $id     = [];
        $column = ['product'];
        foreach ($search as $attribute) {
            $where  = [
                'field' => $attribute['field'],
                'data'  => $attribute['data'],
            ];
            $select = Pi::model('field_data', $this->getModule())->select()->where($where)->columns($column);
            $rowSet = Pi::model('field_data', $this->getModule())->selectWith($select);
            foreach ($rowSet as $row) {
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
        // Get info
        $list   = [
            '' => '',
            0  => __('Hidden'),
        ];
        $order  = ['order ASC', 'id ASC'];
        $select = Pi::model('field_position', $this->getModule())->select()->order($order);
        $rowSet = Pi::model('field_position', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowSet as $row) {
            $list[$row->id] = $row->title;
        }
        return $list;
    }

    public function setCategory($field, $categoryArr)
    {
        // Remove
        Pi::model('field_category', $this->getModule())->delete(['field' => $field]);
        // Add
        foreach ($categoryArr as $category) {
            // Save
            $row           = Pi::model('field_category', $this->getModule())->createRow();
            $row->field    = $field;
            $row->category = $category;
            $row->save();
        }
    }

    public function getCategory($field)
    {
        $category = [];
        $where    = ['field' => $field];
        $select   = Pi::model('field_category', $this->getModule())->select()->where($where);
        $rowSet   = Pi::model('field_category', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $category[] = $row->category;
        }
        return array_unique($category);
    }

    public function getField($category = '')
    {
        $field = [];
        if (!empty($category)) {
            $where = ['category' => [$category, 0]];
        } else {
            $where = ['category' => 0];
        }
        $select = Pi::model('field_category', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('field_category', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $field[] = $row->field;
        }
        return array_unique($field);
    }

    public function filterList($category = '')
    {
        // Set return
        $return = [];
        // Get position list
        $position = $this->attributePositionForm();
        // Get field id from business
        $id = $this->getField($category);
        if (empty($id)) {
            return $return;
        }
        // find
        $whereField = ['status' => 1, 'search' => 1, 'id' => $id];
        $orderField = ['order ASC', 'position ASC', 'id DESC'];
        $select     = Pi::model('field', $this->getModule())->select()->where($whereField)->order($orderField);
        $rowSet     = Pi::model('field', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $filter                  = $row->toArray();
            $filter['position_vew']  = $position[$row->position];
            $filter['value']         = json_decode($row->value, true);
            $filter['value']['data'] = explode('|', $filter['value']['data']);
            $filter['filter']        = sprintf('filter.%s', $row->name);
            $return[]                = $filter;
        }
        return $return;
    }

    public function filterData($productId, $filterList = [], $category = '')
    {
        // Get filter list
        if (empty($filterList)) {
            $filterList = $this->filterList($category = '');
        }
        // Get data list
        $where  = ['product' => $productId];
        $column = ['field', 'data'];
        $select = Pi::model('field_data', $this->getModule())->select()->where($where)->columns($column);
        $rowSet = Pi::model('field_data', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $data[$row->field] = $row->toArray();
        }

        $ret = [];

        foreach ($filterList as $filterSingle) {
            if (isset($data[$filterSingle['id']]['data']) && !empty($data[$filterSingle['id']]['data'])) {
                $ret[$filterSingle['name']] = $data[$filterSingle['id']]['data'];
            } else {
                $ret[$filterSingle['name']] = '';
            }
        }

        return $ret;
    }
}
