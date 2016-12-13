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
 * Pi::api('related', 'shop')->getListId($product);
 * Pi::api('related', 'shop')->getListAll($product);
 * Pi::api('related', 'shop')->findList($values);
 */

class Related extends AbstractApi
{
    /**
     * Get Related all information for selected product
     */
    public function getListAll($product)
    {
        $id = $this->getListId($product);
        $list = array();
        if (!empty($id) && is_array($id)) {
            $list = Pi::api('product', 'shop')->getListFromId($id);
        }
        return $list;
    }

    /**
     * Get related id and title for selected product
     */
    public function getListId($product)
    {
        $list = array();
        $where = array('product_id' => $product);
        $select = Pi::model('related', $this->getModule())->select()->where($where);
        $rowset = Pi::model('related', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $row = $row->toArray();
            $list[] = $row['product_related'];
        }
        return $list;
    }

    /**
     * Get Related all information for selected product
     */
    public function getListFind($id)
    {
        $list = array();
        if (!empty($id) && is_array($id)) {
            $list = Pi::api('product', 'shop')->getListFromId($id);
        }
        return $list;
    }

    public function findList($product, $values)
    {
        $list = array();
        $from_category = array();
        $from_title = array();
        // Find product ids from title
        if (!empty($values['title'])) {
            $from_title = Pi::api('product', 'shop')->searchRelated($values['title'], $values['type']);
        }
        // Find product ids from selected cats
        if (is_array($values['category']) && !empty($values['category'])) {
            $from_category = Pi::api('category', 'shop')->findFromCategory($values['category']);
        }
        // Set array
        $id = array_merge($from_title, $from_category);
        $id = array_unique($id);
        //unset($id[$product]);
        // Get product list
        if (!empty($id)) {
            $list = $this->getListFind($id);
        }
        return $list;
    }
}	