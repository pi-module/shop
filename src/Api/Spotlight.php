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
use Pi\Application\AbstractApi;
use Zend\Json\Json;

/*
 * Pi::api('shop', 'spotlight')->load($category);
 */

class Spotlight extends AbstractApi
{
    public function load($category = -1)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Set options
        $where1 = array('status' => 1, 'time_publish < ?' => time(), 'time_expire > ?' => time()); 
        $where2 = array('category' => 0);
        $where3 = array('category' => $category);
        $order = array('id DESC', 'time_publish DESC');
        $limit = intval($config['view_spotlight_number']);
        $columns = array('product');
        // Get ids
        $model = Pi::model('spotlight', $this->getModule());
        $select = $model->select();
        $select->columns($columns);
        $select->where($where1);
        $select->where($where2);
        $select->where($where3, 'OR');
        $select->order($order);
        $select->limit($limit);
        $rowset = $model->selectWith($select);
        foreach ($rowset as $row) {
            $spotlightId[] = $row->product;
        }
        // Get list of products
        if (!empty($spotlightId)) {
            $spotlight = Pi::api('shop', 'product')->getListFromId($spotlightId);
            return $spotlight;
        }
        return false;
    }
}	