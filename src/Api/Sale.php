<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;

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
        $sale = [];
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
                        $time = $saleInformation['infoAll']['category'][$saleCategory['id']]['time_expire'];

                        $sale[$saleCategory['id']] = $saleCategory;
                        $sale[$saleCategory['id']]['saleInformation'] = $saleInformation['infoAll']['category'][$saleCategory['id']];
                        $sale[$saleCategory['id']]['saleInformation']['price_time'] = [
                            'year'   => date("Y", $time),
                            'month'  => date("m", $time),
                            'day'    => date("d", $time),
                            'hour'   => date("H", $time),
                            'minute' => date("i", $time),
                            'second' => date("s", $time),
                        ];
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