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
 * Pi::api('sale', 'shop')->getAll($limit, $type);
 * Pi::api('sale', 'shop')->getInformation($type);
 */

class Sale extends AbstractApi
{
    public function getAll($limit = 0, $type = 'product')
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        $sale = array();
        // Set options
        if ($limit == 0) {
            $limit = intval($config['sale_view_number']);
        }
        $saleInformation = Pi::registry('saleInformation', 'shop')->read();
        // Get list of products
        switch ($type) {
            case 'product':
                if (!empty($saleInformation['idActive']['product'])) {
                    $sale = Pi::api('product', 'shop')->getListFromId($saleInformation['idActive']['product'], $limit);
                }
                break;

            case 'category':
                if (!empty($saleInformation['idActive']['category'])) {
                    $saleCategoryList = Pi::api('category', 'shop')->getListFromId($saleInformation['idActive']['category'], $limit);
                    foreach ($saleCategoryList as $saleCategory) {
                        $sale[$saleCategory['id']] = $saleCategory;
                        $sale[$saleCategory['id']]['saleInformation'] = $saleInformation['infoAll']['category'][$saleCategory['id']];
                        $sale[$saleCategory['id']]['saleInformation']['time_expire_view'] = date("Y-m-d H:i:s", $saleInformation['infoAll']['category'][$saleCategory['id']]['time_expire']);
                    }
                }
                break;
        }
        return $sale;
    }

    public function getInformation($type = 'active')
    {
        // Get
        $saleInformation = Pi::registry('saleInformation', 'shop')->read();
        // Set result
        switch ($type) {
            case 'active':
                // Check time
                if (isset($saleInformation['timeExpire']) && time() > $saleInformation['timeExpire']) {
                    Pi::registry('saleInformation', 'shop')->clear();
                    $saleInformation = Pi::registry('saleInformation', 'shop')->read();
                }
                // Set id
                $id = $saleInformation['idActive'];
                break;

            case 'expire':
                // Check time
                if (isset($saleInformation['timeExpire']) && time() > $saleInformation['timeExpire']) {
                    Pi::registry('saleInformation', 'shop')->clear();
                    $saleInformation = Pi::registry('saleInformation', 'shop')->read();
                }
                // Set id
                $id = $saleInformation['idExpire'];
                break;

            case 'all':
                $id = $saleInformation['idAll'];
                break;
        }
        return $id;
    }
}