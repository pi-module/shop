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
 * Pi::api('log', 'shop')->addLog($section, $item, $operation);
 */

class Log extends AbstractApi
{
    public function addLog($section, $item, $operation)
    {
        $row = Pi::model('log', $this->getModule())->createRow();
        $row->uid = Pi::user()->getId();
        $row->ip = Pi::user()->getIp();
        $row->time_create = time();
        $row->section = $section;
        $row->item = $item;
        $row->operation = $operation;
        $row->save();
    }
}