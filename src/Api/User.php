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
 * Pi::api('user', 'shop')->findUser();
 * Pi::api('user', 'shop')->setUserInfo($values);
 * Pi::api('user', 'shop')->getUserInfo();
 */

class User extends AbstractApi
{
    public function findUser()
    {
        $uid = Pi::user()->getId();
        $row = Pi::model('user', $this->getModule())->find($uid);
        if (is_object($row)) {
        	$user = $row->toArray();
            $user['user'] = Pi::user()->get($user['uid'], array('id', 'identity', 'name', 'email'));
        } else {
        	$user = array();
        }
        return $user;
    }

    public function setUserInfo($values)
    {
        $uid = Pi::user()->getId();
        $row = Pi::model('user', $this->getModule())->find($uid);
        if (is_object($row)) {
        	$number = $row->number;
        } else {
        	$row = Pi::model('user', $this->getModule())->createRow();
        	$number = 0;
        }
        // Set info
        $row->uid = $uid;
        $row->first_name = ($values['first_name']) ? $values['first_name'] : '';
        $row->last_name = ($values['last_name']) ? $values['last_name'] : '';
        $row->email = ($values['email']) ? $values['email'] : '';
        $row->phone = ($values['phone']) ? $values['phone'] : '';
        $row->mobile = ($values['mobile']) ? $values['mobile'] : '';
        $row->company = ($values['company']) ? $values['company'] : '';
        $row->address = ($values['address']) ? $values['address'] : '';
        $row->country = ($values['country']) ? $values['country'] : '';
        $row->city = ($values['city']) ? $values['city'] : '';
        $row->zip_code = ($values['zip_code']) ? $values['zip_code'] : '';
        $row->number = $number + $values['number'];
        $row->save();
    }

    public function getUserInfo()
    {
    	$user = $this->findUser();
        $values['first_name'] = (isset($user['first_name'])) ? $user['first_name'] : '';
        $values['last_name'] = (isset($user['last_name'])) ? $user['last_name'] : '';
        $values['email'] = (isset($user['email'])) ? $user['email'] : '';
        $values['phone'] = (isset($user['phone'])) ? $user['phone'] : '';
        $values['mobile'] = (isset($user['mobile'])) ? $user['mobile'] : '';
        $values['company'] = (isset($user['company'])) ? $user['company'] : '';
        $values['address'] = (isset($user['address'])) ? $user['address'] : '';
        $values['country'] = (isset($user['country'])) ? $user['country'] : '';
        $values['city'] = (isset($user['city'])) ? $user['city'] : '';
        $values['zip_code'] = (isset($user['zip_code'])) ? $user['zip_code'] : '';
        return $values;
    }
}	