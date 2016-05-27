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
use Module\Shop\Form\SaleForm;
use Module\Shop\Form\SaleFilter;

class SaleController extends ActionController
{
    public function indexAction()
    {
        // Get product and category
        $columns = array('product');
        $select = $this->getModel('sale')->select()->columns($columns);
        $idSet = $this->getModel('sale')->selectWith($select)->toArray();
        if (empty($idSet)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set topics and stores
        foreach ($idSet as $sale) {
            $productArr[] = $sale['product'];
        }
        // Get products
        $where = array('id' => array_unique($productArr));
        $columns = array('id', 'title', 'slug');
        $select = $this->getModel('product')->select()->where($where)->columns($columns);
        $productSet = $this->getModel('product')->selectWith($select);
        // Make product list
        foreach ($productSet as $row) {
            $productList[$row->id] = $row->toArray();
        }
        // Get sale
        $order = array('id DESC', 'time_publish DESC');
        $select = $this->getModel('sale')->select()->order($order);
        $saleSet = $this->getModel('sale')->selectWith($select);
        // Make sale list
        foreach ($saleSet as $row) {
            $saleList[$row->id] = $row->toArray();
            $saleList[$row->id]['productTitle'] = $productList[$row->product]['title'];
            $saleList[$row->id]['productSlug'] = $productList[$row->product]['slug'];
            $saleList[$row->id]['time_publish'] = _date($saleList[$row->id]['time_publish']);
            $saleList[$row->id]['time_expire'] = _date($saleList[$row->id]['time_expire']);
        }
        // Set view
        $this->view()->setTemplate('sale-index');
        $this->view()->assign('sales', $saleList);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        // Set option
        $option = array();
        $option['type'] = $id ? 'edit' : 'add';
        // Set form
        $form = new SaleForm('sale', $option);
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new SaleFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set time
                //$values['time_publish'] = strtotime($values['time_publish']);
                //$values['time_expire'] = strtotime($values['time_expire']);
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('sale')->find($values['id']);
                } else {
                    $row = $this->getModel('sale')->createRow();
                }
                $row->assign($values);
                $row->save();
                // update registry
                Pi::registry('saleListId', 'shop')->clear();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('sale', $row->id, $operation);
                // Check it save or not
                $message = __('Sale data saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            }
        } else {
            if ($id) {
                $values = $this->getModel('sale')->find($id)->toArray();
                $form->setData($values);
            }
        }
        // Set title
        $title = $id ? __('Edit sale') : __('Add sale');
        // Set view
        $this->view()->setTemplate('sale-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', $title);
    }

    public function deleteAction()
    {
        // Get information
        $this->view()->setTemplate(false);
        $id = $this->params('id');
        $row = $this->getModel('sale')->find($id);
        if ($row) {
            $row->delete();
            $this->jump(array('action' => 'index'), __('Selected sale delete'));
        }
        $this->jump(array('action' => 'index'), __('Please select sale'));
    }
}