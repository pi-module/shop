<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Laminas\Db\Sql\Predicate\Expression;

/*
 * Pi::api('company', 'shop')->getProductList($params);
 * Pi::api('company', 'shop')->getProductIdList($params);
 * Pi::api('company', 'shop')->getOrders($params);
 */

class Company extends AbstractApi
{
    public function getProductList($params): array
    {
        // Set info
        $where = ['company_id' => $params['company_id']];
        $order = ['time_create DESC', 'id DESC'];

        // Select
        $select = Pi::model('product', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('product', $this->getModule())->selectWith($select);

        $list = [];
        foreach ($rowSet as $row) {
            $list[$row->id] = Pi::api('product', 'shop')->canonizeProductLight($row);
        }

        return $list;
    }

    public function getProductIdList($params): array
    {
        // Set info
        $where = ['company_id' => $params['company_id']];
        $order = ['time_create DESC', 'id DESC'];
        $columns = ['id'];

        // Select
        $select = Pi::model('product', $this->getModule())->select()->columns($columns)->where($where)->order($order);
        $rowSet = Pi::model('product', $this->getModule())->selectWith($select);

        $list = [];
        foreach ($rowSet as $row) {
            $list[$row->id] = $row->id;
        }

        return $list;
    }

    public function getOrders($params): array
    {
        // Set info
        $where = ['module' => $this->getModule(), 'product_type' => 'product', 'product' => $params['productList']];
        $order = ['id DESC'];
        $columns = ['order'];

        // Select
        $select = Pi::model('detail', 'order')->select()->columns($columns)->where($where)->order($order);
        $rowSet = Pi::model('detail', 'order')->selectWith($select);

        $orderIdList = [];
        foreach ($rowSet as $row) {
            $orderIdList[$row->id] = $row->id;
        }

        // Set info
        $where = ['id' => $orderIdList];

        // Select
        $select = Pi::model('order', 'order')->select()->where($where)->order($order);
        if (isset($params['limit']) && !empty($params['limit'])) {
            $select->limit($params['limit']);
        }
        if (isset($params['offset']) && !empty($params['offset'])) {
            $select->offset($params['offset']);
        }
        $rowSet = Pi::model('order', $this->getModule())->selectWith($select);

        // Make list
        $orders = [];
        foreach ($rowSet as $row) {
            $orders[$row->id] = Pi::api('order', 'order')->canonizeOrder($row);
        }

        // Make order list
        $orderList = [];
        foreach ($orders as $order) {
            $countInstallment      = 0;
            $toPaid                = 0;
            $order['installments'] = Pi::api('installment', 'order')->getInstallmentsFromOrder($order['id']);
            $order['can_pay']      = false;

            foreach ($order['installments'] as $installment) {
                if ($installment['status_invoice'] != \Module\Order\Model\Invoice::STATUS_INVOICE_CANCELLED) {
                    $countInstallment++;
                    if ($installment['status_payment'] == \Module\Order\Model\Invoice\Installment::STATUS_PAYMENT_UNPAID) {
                        $toPaid += $installment['due_price'];
                    }
                    if ($installment['status_payment'] == \Module\Order\Model\Invoice\Installment::STATUS_PAYMENT_UNPAID
                        && $installment['gateway'] != 'manual'
                    ) {
                        $order['can_pay'] = true;
                    }
                }
            }

            $order['to_paid_view'] = Pi::api('api', 'order')->viewPrice($toPaid);

            $products   = Pi::api('order', 'order')->listProduct($order['id'], ['order' => $order]);
            $totalPrice = 0;

            foreach ($products as $product) {
                $totalPrice += $product['product_price'] + $product['shipping_price'] + $product['packing_price'] + $product['setup_price']
                    + $product['vat_price'] - $product['discount_price'];
            }

            $orderList[$order['id']]                     = $order;
            $orderList[$order['id']]['products']         = $products;
            $orderList[$order['id']]['total_price_view'] = Pi::api('api', 'order')->viewPrice($totalPrice);
        }

        return $orderList;
    }
}