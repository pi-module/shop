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
 * Pi::api('customer', 'shop')->getCustomer($uid);
 * Pi::api('customer', 'shop')->canonizeCustomer($customer);
 */

class Customer extends AbstractApi
{
    public function getCustomer($uid = '', $field = array())
    {
        if (empty($uid)) {
            $uid = Pi::user()->getId(); 
        }

        if (empty($field)) {
            $field = array(
                'id', 'identity', 'name', 'email', 'first_name', 'last_name', 'phone', 'mobile', 'credit',
                'address1', 'address2', 'country', 'state', 'city', 'zip_code', 'company', 'company_id', 'company_vat',
                'time_activated'
            );
        }

        // Get user info
        $customer = Pi::user()->get($uid, $field);
        
        return $this->canonizeCustomer($customer);
    }
    
    public function canonizeCustomer($customer)
    {
        // Check
        if (empty($customer)) {
            return '';
        }
        // Check user first_name
        if (!isset($customer['first_name'])) {
            $customer['first_name'] = '';
        }
        // Check user last_name
        if (!isset($customer['last_name'])) {
            $customer['last_name'] = '';
        }
        // Check user phone
        if (!isset($customer['phone'])) {
            $customer['phone'] = '';
        }
        // Check user mobile
        if (!isset($customer['mobile'])) {
            $customer['mobile'] = '';
        }
        // Check user address1
        if (!isset($customer['address1'])) {
            $customer['address1'] = '';
        }
        // Check user address2
        if (!isset($customer['address2'])) {
            $customer['address2'] = '';
        }
        // Check user country
        if (!isset($customer['country'])) {
            $customer['country'] = '';
        }
        // Check user state
        if (!isset($customer['state'])) {
            $customer['state'] = '';
        }
        // Check user city
        if (!isset($customer['city'])) {
            $customer['city'] = '';
        }
        // Check user zip_code
        if (!isset($customer['zip_code'])) {
            $customer['zip_code'] = '';
        }
        // Check user company
        if (!isset($customer['company'])) {
            $customer['company'] = '';
        }
        // Check user company_id
        if (!isset($customer['company_id'])) {
            $customer['company_id'] = '';
        }
        // Check user company_vat
        if (!isset($customer['company_vat'])) {
            $customer['company_vat'] = '';
        }
        // display name
        if (!empty($customer['first_name']) && !empty($customer['last_name'])) {
            $customer['display'] = sprintf('%s %s', $customer['first_name'], $customer['last_name']);
        } else {
            $customer['display'] = $customer['name'];
        }

        // avatar
        $customer['avatar'] = Pi::service('user')->avatar($customer['id'], 'medium', $customer['display']);
        // profile url
        $customer['profileUrl'] = Pi::url(Pi::service('user')->getUrl('profile', array(
            'id' => $customer['id'],
        )));
        // account url
        $customer['accountUrl'] = Pi::url(Pi::service('user')->getUrl(
            'user', array('controller' => 'account')
        ));
        // return
        return $customer;
    }
}
