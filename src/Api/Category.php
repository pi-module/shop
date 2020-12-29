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
use Laminas\Db\Sql\Predicate\Expression;

/*
 * Pi::api('category', 'shop')->getCategory($parameter, $type = 'id');
 * Pi::api('category', 'shop')->getChildCount($parent);
 * Pi::api('category', 'shop')->setLink($product, $category, $create, $update, $price, $stock, $status, $recommended, $code);
 * Pi::api('category', 'shop')->findFromCategory($category);
 * Pi::api('category', 'shop')->categoryListByParent($parent);
 * Pi::api('category', 'shop')->categoryListJson();
 * Pi::api('category', 'shop')->categoryList($params);
 * Pi::api('category', 'shop')->categoryCount();
 * Pi::api('category', 'shop')->canonizeCategory($category);
 * Pi::api('category', 'shop')->sitemap();
 * Pi::api('category', 'shop')->makeTree($elements, $parentId);
 * Pi::api('category', 'shop')->makeTreeOrder($elements, $parentId = 0);
 */

class Category extends AbstractApi
{
    public function getCategory($parameter, $type = 'id')
    {
        $category = Pi::model('category', $this->getModule())->find($parameter, $type);
        $category = $this->canonizeCategory($category);
        return $category;
    }

    public function getChildCount($parent)
    {
        $where   = ['parent' => $parent, 'status' => 1];
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('category', $this->getModule())->select()->columns($columns)->where($where);
        $count   = Pi::model('category', $this->getModule())->selectWith($select)->current()->count;
        return $count;
    }

    public function setLink(
        $product,
        $category,
        $create,
        $update,
        $price,
        $stock,
        $status,
        $recommended = 0,
        $code = null
    ) {
        //Remove
        Pi::model('link', $this->getModule())->delete(['product' => $product]);
        // Add
        $allCategory = json_decode($category, true);
        foreach ($allCategory as $category) {
            // Set array
            $values['product']     = $product;
            $values['category']    = $category;
            $values['time_create'] = $create;
            $values['time_update'] = $update;
            $values['price']       = $price;
            $values['stock']       = ($stock > 0) ? 1 : 0;
            $values['status']      = $status;
            $values['recommended'] = $recommended;
            $values['code']        = $code;
            // Save
            $row = Pi::model('link', $this->getModule())->createRow();
            $row->assign($values);
            $row->save();
        }
    }

    public function findFromCategory($category)
    {
        $list   = [];
        $where  = ['category' => $category];
        $select = Pi::model('link', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('link', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $row    = $row->toArray();
            $list[] = $row['product'];
        }
        return array_unique($list);
    }

    public function getListFromId($id, $limit = 0)
    {
        $list   = [];
        $where  = ['id' => $id, 'status' => 1];
        $order  = ['display_order DESC', 'id DESC'];
        $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
        if ($limit > 0) {
            $select->limit($limit);
        }
        $rowSet = Pi::model('category', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $this->canonizeCategory($row);
        }
        return $list;
    }

    public function categoryListByParent($parent = 0)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        $return = [];
        $where  = ['status' => 1];
        $order  = ['display_order ASC'];

        // Make select
        $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('category', $this->getModule())->selectWith($select);

        // Set list
        foreach ($rowSet as $row) {

            $thumbUrl = '';
            if ($row->image) {
                $thumbUrl = Pi::url(
                    sprintf(
                        'upload/%s/thumb/%s/%s',
                        $config['image_path'],
                        $return[$row->id]['path'],
                        $return[$row->id]['image']
                    )
                );
            }

            $return[] = [
                'id'       => $row->id,
                'parent'   => $row->parent,
                'text'     => $row->title,
                'thumbUrl' => $thumbUrl,
                'href'     => Pi::url(
                    Pi::service('url')->assemble(
                        'shop', [
                            'module'     => $this->getModule(),
                            'controller' => 'category',
                            'slug'       => $row->slug,
                        ]
                    )
                ),
            ];
        }
        $return = $this->makeTreeList($return, $parent);
        return $return;
    }

    public function categoryListJson()
    {
        $return = [];
        $where  = ['status' => 1];
        $order  = ['display_order ASC'];
        // Make list
        $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('category', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $return[] = [
                'id'     => $row->id,
                'parent' => $row->parent,
                'text'   => $row->title,
                'href'   => Pi::url(
                    Pi::service('url')->assemble(
                        'shop', [
                            'module'     => $this->getModule(),
                            'controller' => 'category',
                            'slug'       => $row->slug,
                        ]
                    )
                ),
            ];
        }
        $return = $this->makeTree($return);
        $return = json_encode($return);
        return $return;
    }

    public function categoryList($params)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Set info
        $list  = [];
        $where = ['status' => 1];
        $order = ['display_order DESC', 'title ASC', 'id DESC'];

        // Check type
        if (isset($params['type']) && in_array($params['type'], ['category', 'brand'])) {
            $where['type'] = $params['type'];
        }

        // Check parent
        if (isset($params['parent']) && is_numeric($params['parent'])) {
            $where['parent'] = $params['parent'];
        }

        // Select
        $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('category', $this->getModule())->selectWith($select);

        // Make list
        foreach ($rowSet as $row) {
            $list[$row->id] = Pi::api('category', 'shop')->canonizeCategory($row);
        }

        return $list;
    }

    public function categoryCount()
    {
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('category', $this->getModule())->select()->columns($columns);
        $count   = Pi::model('category', $this->getModule())->selectWith($select)->current()->count;
        return $count;
    }

    public function canonizeCategory($category)
    {
        // Check
        if (empty($category)) {
            return '';
        }

        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // boject to array
        $category = $category->toArray();

        // Set text_description
        $category['text_description'] = Pi::service('markup')->render($category['text_description'], 'html', 'html');

        // Set times
        $category['time_create_view'] = _date($category['time_create']);
        $category['time_update_view'] = _date($category['time_update']);

        // Set item url
        $category['categoryUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'category',
                    'slug'       => $category['slug'],
                ]
            )
        );

        // Set image url
        if ($category['image']) {

            // Set image original url
            $category['originalUrl'] = Pi::url(
                sprintf(
                    'upload/%s/original/%s/%s',
                    $config['image_path'],
                    $category['path'],
                    $category['image']
                )
            );

            // Set image large url
            $category['largeUrl'] = Pi::url(
                sprintf(
                    'upload/%s/large/%s/%s',
                    $config['image_path'],
                    $category['path'],
                    $category['image']
                )
            );

            // Set image medium url
            $category['mediumUrl'] = Pi::url(
                sprintf(
                    'upload/%s/medium/%s/%s',
                    $config['image_path'],
                    $category['path'],
                    $category['image']
                )
            );

            // Set image thumb url
            $category['thumbUrl'] = Pi::url(
                sprintf(
                    'upload/%s/thumb/%s/%s',
                    $config['image_path'],
                    $category['path'],
                    $category['image']
                )
            );
        }

        // return category
        return $category;
    }

    public function sitemap()
    {
        if (Pi::service('module')->isActive('sitemap')) {
            // Remove old links
            Pi::api('sitemap', 'sitemap')->removeAll($this->getModule(), 'category');
            // find and import
            $columns = ['id', 'slug', 'status'];
            $select  = Pi::model('category', $this->getModule())->select()->columns($columns);
            $rowSet  = Pi::model('category', $this->getModule())->selectWith($select);
            foreach ($rowSet as $row) {
                // Make url
                $loc = Pi::url(
                    Pi::service('url')->assemble(
                        'shop', [
                            'module'     => $this->getModule(),
                            'controller' => 'category',
                            'slug'       => $row->slug,
                        ]
                    )
                );
                // Add to sitemap
                Pi::api('sitemap', 'sitemap')->groupLink($loc, $row->status, $this->getModule(), 'category', $row->id);
            }
        }
    }

    public function makeTree($elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $children = $this->makeTree($elements, $element['id']);
                if ($children) {
                    $element['nodes'] = $children;
                }
                $branch[] = $element;
                unset($elements[$element['id']]);
                unset($depth);
            }
        }
        return $branch;
    }

    public function makeTreeList($elements, $parentId = 0)
    {
        $branch = [];
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $branch[] = $element;
                $children = $this->makeTree($elements, $element['id']);
                if ($children) {
                    $branch = array_merge($branch, $children);
                }
                //$branch[] = $element;
                unset($elements[$element['id']]);
                //unset($depth);
            }
        }
        return $branch;
    }

    public function makeTreeOrder($elements, $parentId = 0)
    {
        $branch = [];
        // Set category list as tree
        foreach ($elements as $element) {
            if ($element['parent'] == $parentId) {
                $depth                  = 0;
                $branch[$element['id']] = $element;
                $children               = $this->makeTreeOrder($elements, $element['id']);
                if ($children) {
                    $depth++;
                    foreach ($children as $key => $value) {
                        $branch[$key] = $value;
                    }
                }
                unset($elements[$element['id']]);
                unset($depth);
            }
        }
        return $branch;
    }
}