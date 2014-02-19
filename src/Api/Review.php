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
 * Pi::api('review', 'shop')->official($id);
 * Pi::api('review', 'shop')->hasOfficial($id);
 * Pi::api('review', 'shop')->listReview($id, $status);
 * Pi::api('review', 'shop')->pendingReview();
 */

class Review extends AbstractApi
{
	public function official($id)
	{
    	$where = array('product' => $id, 'official' => 1);
    	$select = Pi::model('review', $this->getModule())->select()->where($where)->limit(1);
    	$row = Pi::model('review', $this->getModule())->selectWith($select)->current();
    	if (!empty($row) && is_object($row)) {
    		$row = $row->toArray();
    		$row['description'] = Pi::service('markup')->render($row['description'], 'text', 'html');
    		$row['time_create_view'] = _date($row['time_create']);
            $row['userinfo'] = Pi::user()->get($row['uid'], array('id', 'identity', 'name', 'email'));
    	}
    	return $row;
	}

	public function hasOfficial($id)
	{
		$official = $this->official($id);
		if (empty($official)) {
			return false;
		} else {
			return true;
		}
	}

	public function listReview($id, $status = null)
	{
    	$list = array();
        if (empty($status)) {
    		$where = array('product' => $id, 'official' => 0);
    	} else {
    		$where = array('product' => $id, 'official' => 0, 'status' => $status);
    	}
    	$select = Pi::model('review', $this->getModule())->select()->where($where);
    	$rowset = Pi::model('review', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
    		$list[$row->id]['description'] = Pi::service('markup')->render($row['description'], 'text', 'html');
    		$list[$row->id]['time_create_view'] = _date($row->time_create);
            $list[$row->id]['userinfo'] = Pi::user()->get($row->uid, array('id', 'identity', 'name', 'email'));
        }
    	return $list;
	}

    public function pendingReview()
    {
        $list = array();
        $where = array('status' => 2);
        $select = Pi::model('review', $this->getModule())->select()->where($where);
        $rowset = Pi::model('review', $this->getModule())->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
            $list[$row->id]['description'] = Pi::service('markup')->render($row['description'], 'text', 'html');
            $list[$row->id]['time_create_view'] = _date($row->time_create);
            $list[$row->id]['userinfo'] = Pi::user()->get($row->uid, array('id', 'identity', 'name', 'email'));
            $list[$row->id]['productinfo'] = Pi::api('product', 'shop')->getProduct($row->product);
        }
        return $list;
    }
}