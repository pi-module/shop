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
 * Pi::api('category', 'shop')->getCategory($parameter, $type = 'id');
 * Pi::api('category', 'shop')->setLink($product, $category, $create, $update, $price, $stock, $status);
 * Pi::api('category', 'shop')->findFromCategory($category);
 * Pi::api('category', 'shop')->categoryList($parent);
 * Pi::api('category', 'shop')->categoryCount();
 * Pi::api('category', 'shop')->canonizeCategory($category);
 * Pi::api('category', 'shop')->sitemap();
 */

class Category extends AbstractApi
{
    public function getCategory($parameter, $type = 'id')
    {
        $category = Pi::model('category', $this->getModule())->find($parameter, $type);
        $category = $this->canonizeCategory($category);
        return $category;
    }

    /**
     * Set product category to link table
     */
    public function setLink($product, $category, $create, $update, $price, $stock, $status)
    {
        //Remove
        Pi::model('link', $this->getModule())->delete(array('product' => $product));
        // Add
        $allCategory = Json::decode($category);
        foreach ($allCategory as $category) {
            // Set array
            $values['product'] = $product;
            $values['category'] = $category;
            $values['time_create'] = $create;
            $values['time_update'] = $update;
            $values['price'] = $price;
            $values['stock'] = ($stock > 0) ? 1 : 0;
            $values['status'] = $status;
            // Save
            $row = Pi::model('link', $this->getModule())->createRow();
            $row->assign($values);
            $row->save();
        }
    }

    public function findFromCategory($category)
    {
        $list = array();
        $where = array('category' => $category);
        $select = Pi::model('link', $this->getModule())->select()->where($where);
        $rowset = Pi::model('link', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $row = $row->toArray();
            $list[] = $row['product'];
        }
        return array_unique($list);
    }

    public function categoryList($parent = null)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        $return = array();
        if (is_null($parent)) {
            $where = array('status' => 1);
        } else {
            $where = array('status' => 1, 'parent' => $parent);
        }
        $order = array('display_order DESC', 'id DESC');
        $select = Pi::model('category', $this->getModule())->select()->where($where)->order($order);
        $rowset = Pi::model('category', $this->getModule())->selectWith($select);
        foreach ($rowset as $row) {
            $return[$row->id] = $row->toArray();
            $return[$row->id]['url'] = Pi::url(Pi::service('url')->assemble('shop', array(
                'module' => $this->getModule(),
                'controller' => 'category',
                'slug' => $return[$row->id]['slug'],
            )));
            $return[$row->id]['thumbUrl'] = Pi::url(
                sprintf('upload/%s/thumb/%s/%s',
                    $config['image_path'],
                    $return[$row->id]['path'],
                    $return[$row->id]['image']
                ));
        }
        return $return;
    }

    public function categoryCount()
    {
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = Pi::model('category', $this->getModule())->select()->columns($columns);
        $count = Pi::model('category', $this->getModule())->selectWith($select)->current()->count;
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
        $category['categoryUrl'] = Pi::url(Pi::service('url')->assemble('shop', array(
            'module' => $this->getModule(),
            'controller' => 'category',
            'slug' => $category['slug'],
        )));
        // Set image url
        if ($category['image']) {
            // Set image original url
            $category['originalUrl'] = Pi::url(
                sprintf('upload/%s/original/%s/%s',
                    $config['image_path'],
                    $category['path'],
                    $category['image']
                ));
            // Set image large url
            $category['largeUrl'] = Pi::url(
                sprintf('upload/%s/large/%s/%s',
                    $config['image_path'],
                    $category['path'],
                    $category['image']
                ));
            // Set image medium url
            $category['mediumUrl'] = Pi::url(
                sprintf('upload/%s/medium/%s/%s',
                    $config['image_path'],
                    $category['path'],
                    $category['image']
                ));
            // Set image thumb url
            $category['thumbUrl'] = Pi::url(
                sprintf('upload/%s/thumb/%s/%s',
                    $config['image_path'],
                    $category['path'],
                    $category['image']
                ));
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
            $columns = array('id', 'slug', 'status');
            $select = Pi::model('category', $this->getModule())->select()->columns($columns);
            $rowset = Pi::model('category', $this->getModule())->selectWith($select);
            foreach ($rowset as $row) {
                // Make url
                $loc = Pi::url(Pi::service('url')->assemble('shop', array(
                    'module' => $this->getModule(),
                    'controller' => 'category',
                    'slug' => $row->slug,
                )));
                // Add to sitemap
                Pi::api('sitemap', 'sitemap')->groupLink($loc, $row->status, $this->getModule(), 'category', $row->id);
            }
        }
    }
}