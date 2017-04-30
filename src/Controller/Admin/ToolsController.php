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
namespace Module\Shop\Controller\Admin;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Shop\Form\SitemapForm;
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
}