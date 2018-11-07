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

namespace Module\Shop\Controller\Feed;

use Pi;
use Pi\Mvc\Controller\FeedController;

class IndexController extends FeedController
{
    public function indexAction()
    {
        $feed   = $this->getDataModel(
            [
                'title'        => __('Shop feed'),
                'description'  => __('Recent products.'),
                'date_created' => time(),
            ]
        );
        $order  = ['time_create DESC', 'id DESC'];
        $where  = ['status' => 1];
        $select = $this->getModel('product')->select()->where($where)->order($order)->limit(10);
        $rowset = $this->getModel('product')->selectWith($select);
        foreach ($rowset as $row) {
            $entry                  = [];
            $entry['title']         = $row->title;
            $description            = (empty($row->text_summary)) ? $row->text_description : $row->text_summary;
            $entry['description']   = strtolower(trim($description));
            $entry['date_modified'] = (int)$row->time_create;
            $entry['link']          = Pi::url(
                Pi::service('url')->assemble(
                    'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'product',
                    'slug'       => $row->slug,
                ]
                )
            );
            $feed->entry            = $entry;
        }
        return $feed;
    }
}