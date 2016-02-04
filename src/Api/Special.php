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
use Zend\Json\Json;

/*
 * Pi::api('special', 'shop')->getAll();
 * Pi::api('special', 'shop')->getIds();
 */

class Special extends AbstractApi
{
    public function getAll($limit = 0)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        $special = array();
        // Set options
        $where = array('status' => 1);
        $order = array('id DESC');
        $columns = array('product');
        if ($limit == 0) {
            $limit = intval($config['view_special_number']);
        }
        // Get ids
        $model = Pi::model('special', $this->getModule());
        $select = $model->select()->where($where)->columns($columns)->order($order)->limit($limit);
        $rowset = $model->selectWith($select);
        foreach ($rowset as $row) {
            $specialId[] = $row->product;
        }
        // Get list of products
        if (!empty($specialId)) {
            $special = Pi::api('product', 'shop')->getListFromId($specialId);
        }
        return $special;
    }

    public function getIds()
    {
        // Set options
        $special = array();
        $where = array('status' => 1);
        $order = array('id DESC');
        $columns = array('product');
        // Get ids
        $model = Pi::model('special', $this->getModule());
        $select = $model->select()->where($where)->columns($columns)->order($order);
        $rowset = $model->selectWith($select);
        foreach ($rowset as $row) {
            $special[] = $row->product;
        }
        return $special;
    }
}