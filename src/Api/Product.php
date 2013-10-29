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
 * Pi::api('shop', 'product')->getListFromId($id);
 * Pi::api('shop', 'product')->searchRelated($title, $type);
 * Pi::api('shop', 'product')->ExtraCount($id);
 */

class Product extends AbstractApi
{
    public function searchRelated($title, $type)
    {
    	$list = array();
    	switch ($type) {
    		case 1:
    		    $where = array('title LIKE ?' => '%' . $title . '%');
    			break;

    		case 2:
    		    $where = array('title LIKE ?' => $title . '%');
    			break;
    			
    		case 3:
    		    $where = array('title LIKE ?' => '%' . $title);
    			break;
    			
    		case 4:
    		    $where = array('title' => $title);
    			break;			
    	}
    	$columns = array('id');
    	$select = Pi::model('product', $this->getModule())->select()->where($where)->columns($columns);
    	$rowset = Pi::model('product', $this->getModule())->selectWith($select);
    	foreach ($rowset as $row) {
            $list[] = $row->id;
        }
        return $list;
    }

    public function getListFromId($id)
    {
    	$list = array();
    	$where = array('id' => $id, 'status' => 1);
    	$select = Pi::model('product', $this->getModule())->select()->where($where);
    	$rowset = Pi::model('product', $this->getModule())->selectWith($select);
    	foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        return $list;
    }	

    /**
     * Set number of used extra fields for selected story
     */
    public function ExtraCount($id)
    {
        // Get attach count
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('field_data', $this->getModule())->select()->columns($columns);
        $count = Pi::model('field_data', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('product', $this->getModule())->update(array('extra' => $count), array('id' => $id));
    }
}	