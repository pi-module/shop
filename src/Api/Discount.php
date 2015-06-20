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
 * Pi::api('discount', 'shop')->getList();
 */

class Discount extends AbstractApi
{
    public function getList()
    {
        // find
        $list = array();
        $where = array('status' => 1);
        $select = Pi::model('discount', $this->getModule())->select()->where($where);
        $rowset = Pi::model('discount', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        return $list;
    }
}