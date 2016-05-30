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
 * Pi::api('sale', 'shop')->getAll($limit);
 * Pi::api('sale', 'shop')->getInformation($type);
 */

class Sale extends AbstractApi
{
    public function getAll($limit = 0)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        $sale = array();
        // Set options
        if ($limit == 0) {
            $limit = intval($config['view_sale_number']);
        }
        $saleInformation = Pi::registry('saleInformation', 'shop')->read();
        // Get list of products
        if (!empty($saleInformation['idActive'])) {
            $sale = Pi::api('product', 'shop')->getListFromId($saleInformation['idActive'], $limit);
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

            case 'all':
                $id = $saleInformation['idAll'];
                break;
        }
        return $id;
    }
}