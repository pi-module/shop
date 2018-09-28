<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt BSD 3-Clause License
 * @package         Registry
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Registry;

use Pi;
use Pi\Application\Registry\AbstractRegistry;

class SaleInformation extends AbstractRegistry
{
    /** @var string Module name */
    protected $module = 'shop';

    /**
     * {@inheritDoc}
     */
    protected function loadDynamic($options = [])
    {
        // Set return
        $return = [
            'infoAll'    => [
                'product'  => [],
                'category' => [],
            ],
            'idAll'      => [
                'product'  => [],
                'category' => [],
            ],
            'idActive'   => [
                'product'  => [],
                'category' => [],
            ],
            'idExpire'   => [
                'product'  => [],
                'category' => [],
            ],
            'timeCheck'  => time(),
            'timeExpire' => time(),
        ];
        $timeExpire = [];
        // Get ids
        $where = ['status' => 1];
        $model = Pi::model('sale', $this->module);
        $select = $model->select()->where($where);
        $rowset = $model->selectWith($select);
        foreach ($rowset as $row) {

            if ($row->type == 'product') {
                $return['infoAll']['product'][$row->product] = $row->toArray();
                $return['idAll']['product'][$row->product] = $row->product;
            } elseif ($row->type == 'category') {
                $return['infoAll']['category'][$row->category] = $row->toArray();
                $return['idAll']['category'][$row->category] = $row->category;
            }

            if ($row->time_publish < time() && $row->time_expire > time()) {
                if ($row->type == 'product') {
                    $return['idActive']['product'][$row->product] = $row->product;
                } elseif ($row->type == 'category') {
                    $return['idActive']['category'][$row->category] = $row->category;
                }
                $timeExpire[$row->time_expire] = $row->time_expire;
            }

            if (time() > $row->time_expire) {
                if ($row->type == 'product') {
                    $return['idExpire']['product'][$row->product] = $row->product;
                } elseif ($row->type == 'category') {
                    $return['idExpire']['category'][$row->category] = $row->category;
                }
            }

        }
        // Set time expire
        if (!empty($timeExpire)) {
            $return['timeExpire'] = min($timeExpire);
        }
        // return
        return $return;
    }

    /**
     * {@inheritDoc}
     * @param array
     */
    public function read()
    {
        $options = [];
        $result = $this->loadData($options);
        return $result;
    }

    /**
     * {@inheritDoc}
     * @param bool $name
     */
    public function create()
    {
        $this->clear('');
        $this->read();

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function setNamespace($meta = '')
    {
        return parent::setNamespace('');
    }

    /**
     * {@inheritDoc}
     */
    public function flush()
    {
        return $this->clear('');
    }
}