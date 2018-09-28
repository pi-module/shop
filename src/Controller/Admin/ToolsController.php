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

namespace Module\Shop\Controller\Admin;

use Module\Shop\Form\SitemapForm;
use Pi;
use Pi\Mvc\Controller\ActionController;
use Zend\Db\Sql\Predicate\Expression;

class ToolsController extends ActionController
{
    public function indexAction()
    {
        // Set template
        $this->view()->setTemplate('tools-index');
    }

    public function sitemapAction()
    {
        $form = new SitemapForm('sitemap');
        $message = __('Rebuild thie module links on sitemap module tabels');
        if ($this->request->isPost()) {
            // Set form date
            $values = $this->request->getPost()->toArray();
            switch ($values['type']) {
                case '1':
                    Pi::api('product', 'shop')->sitemap();
                    Pi::api('category', 'shop')->sitemap();
                    break;

                case '2':
                    Pi::api('product', 'shop')->sitemap();
                    break;

                case '3':
                    Pi::api('category', 'shop')->sitemap();
                    break;
            }
            $message = __('Sitemap rebuild finished');
        }
        // Set view
        $this->view()->setTemplate('tools-sitemap');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Rebuild sitemap links'));
        $this->view()->assign('message', $message);
    }

    /* public function importAction()
    {
        $file = '/var/www/html/local/test/shobadebaz/product.csv';
        $productData = array();
        $row = 1;
        if (($handle = fopen($file, "r")) !== false) {
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                $num = count($data);
                $i = 1;
                for ($c = 0; $c < $num; $c++) {
                    $productData[$row][$i] = $data[$c];
                    $i++;
                }
                $row++;
            }
            fclose($handle);
        }
        // Make user field list
        $fieldList = array_shift($productData);

        echo '<pre>';
        print_r($fieldList);
        echo '</pre>';


        //$path = sprintf('%s/%s', date('Y'), date('m'));
        //$originalPath = Pi::path(sprintf('upload/shop/image//original/%s', $path));

        foreach ($productData as $product) {

            $values = array();
            $values['title'] = $product[5];
            $values['slug'] = empty($product[7]) ? md5(rand(1000, 99999)) : urldecode($product[7]);
            $values['text_description'] = $product[10];
            $values['price'] = $product[19];
            $values['uid'] = Pi::user()->getId();
            $values['time_create'] = time();
            $values['time_update'] = time();

            if (!empty($product[33])) {
                // Set
                $key = \Zend\Math\Rand::getString(16, 'abcdefghijklmnopqrstuvwxyz123456789', true);
                $image = sprintf('%s.jpg', $key);
                $originalImage = sprintf('%s/%s', $originalPath, $image);
                // download image
                Pi::service('remote')->download($product[33], $originalImage);
                // Resize image
                Pi::api('image', 'shop')->process($image, $path);

                $values['image'] = $image;
                $values['path'] = $path;
            }

            $row = $this->getModel('product')->createRow();
            $row->assign($values);
            $row->save();
        }



        //5 Title
        //7 Slug
        //10 Description
        //33 image
        //19 price

        // Set view
        $this->view()->setTemplate(false);
    } */

    /*
     * Script for build osport main category
     */
    /* public function osport()
    {
        $catList = array();
        $where = array('parent' => 88);
        $select = $this->getModel('category')->select()->where($where);
        $rowset = $this->getModel('category')->selectWith($select);
        foreach ($rowset as $row) {
            $catList[$row->id] = array(
                'id' => $row->id,
                'title' => $row->title,
                'count' => 0,
            );
        }

        foreach ($catList as $category) {
            $whereLink = array('category' => $category['id']);
            $select = $this->getModel('link')->select()->where($whereLink);
            $count = $this->getModel('link')->selectWith($select);
            foreach ($count as $link) {
                $catList[$category['id']]['count']++;
                $catList[$category['id']]['product'][$link->product] = $link->product;
            }

            if ($catList[$category['id']]['count'] > 0) {
                $this->getModel('product')->update(
                    array('category_main' => $category['id']),
                    array('id' => $catList[$category['id']]['product'])
                );
            }
        }
    } */

    /* public function codeAction()
    {
        // Get info
        $start = $this->params('start', 0);
        $count = $this->params('count');
        $complete = $this->params('complete', 0);
        $confirm = $this->params('confirm', 0);

        // Check confirm
        if ($confirm == 1) {
            // Get products and send
            $where = array(
                'field' => 1,
                'id > ?' => $start,
            );
            $order = array('id ASC');

            $select = $this->getModel('field_data')->select()->where($where)->order($order)->limit(100);
            $rowset = $this->getModel('field_data')->selectWith($select);

            $ddd = array();

            // Make list
            foreach ($rowset as $row) {

                $whereProduct = array('code' => $row->data);
                $columnsProduct = array('count' => new Expression('count(*)'));
                $select = $this->getModel('product')->select()->where($whereProduct)->columns($columnsProduct);
                $countCode = $this->getModel('product')->selectWith($select)->current()->count;

                $ddd[$row->product] = array(
                    'count' => $countCode,
                    'product' => $row->product,
                    'code' => $row->data
                );

                if (intval($countCode) < 1) {
                    $this->getModel('product')->update(
                        array('code' => $row->data),
                        array('id' => (int)$row->product)
                    );
                }

                // Set extra
                $lastId = (int)$row->id;
                $complete++;
            }
            // Get count
            if (!$count) {
                $where = array(
                    'field' => 1,
                );
                $columns = array('count' => new Expression('count(*)'));
                $select = $this->getModel('field_data')->select()->where($where)->columns($columns);
                $count = $this->getModel('field_data')->selectWith($select)->current()->count;
            }
            // Set complete
            $percent = (100 * $complete) / $count;
            // Set next url
            if ($complete >= $count) {
                $nextUrl = '';
            } else {
                $nextUrl = Pi::url($this->url('', array(
                    'action' => 'code',
                    'start' => $lastId,
                    'count' => $count,
                    'complete' => $complete,
                    'confirm' => $confirm,
                )));
            }

            $info = array(
                'start' => $lastId,
                'count' => $count,
                'complete' => $complete,
                'percent' => $percent,
                'nextUrl' => $nextUrl,
                'ddd' => $ddd,
            );

            $percent = ($percent > 99 && $percent < 100) ? (intval($percent) + 1) : intval($percent);
        } else {
            $info = array();
            $percent = 0;
            $nextUrl = Pi::url($this->url('', array(
                'action' => 'code',
                'confirm' => 1,
            )));
        }
        // Set view
        $this->view()->setTemplate('category-sync');
        $this->view()->assign('nextUrl', $nextUrl);
        $this->view()->assign('percent', $percent);
        $this->view()->assign('info', $info);
        $this->view()->assign('confirm', $confirm);
    } */
}