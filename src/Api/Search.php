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
use Pi\Search\AbstractSearch;

class Search extends AbstractSearch
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'product';

    /**
     * {@inheritDoc}
     */
    protected $searchIn = array(
        'title',
        'subtitle',
        'text_summary',
        'text_description',
    );

    /**
     * {@inheritDoc}
     */
    protected $meta = array(
        'id' => 'id',
        'title' => 'title',
        'text_summary' => 'content',
        'time_create' => 'time',
        'uid' => 'uid',
        'slug' => 'slug',
        'image' => 'image',
        'path' => 'path',
    );

    /**
     * {@inheritDoc}
     */
    protected $condition = array(
        'status' => 1,
    );

    /**
     * {@inheritDoc}
     */
    protected function buildUrl(array $item, $table)
    {
        switch ($table) {
            case 'category':
                $link = Pi::url(Pi::service('url')->assemble('shop', array(
                    'module' => $this->getModule(),
                    'controller' => 'category',
                    'slug' => $item['slug'],
                )));
                break;

            case 'product':
                $link = Pi::url(Pi::service('url')->assemble('shop', array(
                    'module' => $this->getModule(),
                    'controller' => 'product',
                    'slug' => $item['slug'],
                )));
                break;
        }

        return $link;
    }

    /**
     * {@inheritDoc}
     */
    protected function buildImage(array $item)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        $image = '';
        if (isset($item['image']) && !empty($item['image'])) {
            $image = Pi::url(
                sprintf('upload/%s/thumb/%s/%s',
                    $config['image_path'],
                    $item['path'],
                    $item['image']
                ));
        }

        return $image;
    }

    /**
     * {@inheritDoc}
     */
    public function query(
        $terms,
        $limit  = 0,
        $offset = 0,
        array $condition = array()
    ) {
        $terms = (array) $terms;
        $dataAll = array();
        $countAll = 0;
        // model list
        $tableList = array(
            'category', 'product'
        );
        // Make query on all tables
        foreach ($tableList as $table) {
            $model = Pi::model($table, $this->module);
            $where = $this->buildCondition($terms, $condition);
            $count = $model->count($where);
            if ($count) {
                $data = $this->fetchResult($model, $where, $limit, $offset, $table);
                $dataAll = array_merge($dataAll, $data);
                $countAll = $countAll + $count;
            }
        }
        $result = $this->buildResult($countAll, $dataAll);
        return $result;
    }

    /**
     * {@inheritDoc}
     */
    protected function fetchResult(
        $model,
        $where,
        $limit = 0,
        $offset = 0,
        $table
    ) {
        $data = array();
        $select = $model->select();
        $select->where($where);
        $select->columns(array_keys($this->meta));
        $select->limit($limit)->offset($offset);
        if ($this->order) {
            $select->order($this->order);
        }
        $rowset = $model->selectWith($select);
        foreach ($rowset as $row) {
            $item = array();
            foreach ($this->meta as $column => $field) {
                $item[$field] = $row[$column];
                if ('content' == $field) {
                    $item[$field] = $this->buildContent($item[$field]);
                }
            }
            $item['url'] = $this->buildUrl($item, $table);
            $item['image'] = $this->buildImage($item);
            $data[] = $item;
        }

        return $data;
    }
}
