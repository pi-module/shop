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
 * Pi::api('sale', 'shop')->getAll();
 * Pi::api('sale', 'shop')->getId();
 */

class Sale extends AbstractApi
{
    public function getAll($limit = 0)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        $sale = array();
        // Set options
        $where = array('status' => 1);
        $order = array('id DESC');
        $columns = array('product');
        if ($limit == 0) {
            $limit = intval($config['view_sale_number']);
        }
        // Get ids
        $model = Pi::model('sale', $this->getModule());
        $select = $model->select()->where($where)->columns($columns)->order($order)->limit($limit);
        $rowset = $model->selectWith($select);
        foreach ($rowset as $row) {
            $saleId[] = $row->product;
        }
        // Get list of products
        if (!empty($saleId)) {
            $sale = Pi::api('product', 'shop')->getListFromId($saleId);
        }
        return $sale;
    }

    public function getId($type = 'active')
    {
        // Get
        $saleId = Pi::registry('saleId', 'shop')->read();
        // Check time
        if (time() > $saleId['timeExpire']) {
            Pi::registry('saleId', 'shop')->clear();
            $saleId = Pi::registry('saleId', 'shop')->read();
        }
        // Set result
        switch ($type) {
            case 'active':
                $id = $saleId['idActive'];
                break;

            case 'all':
                $id = $saleId['idAll'];
                break;
        }
        return $id;
    }
}