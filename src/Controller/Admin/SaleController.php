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
        // Set array
        $saleList = array();
        // Get product ides
        $saleId = Pi::api('sale', 'shop')->getInformation('all');
        if (!empty($saleId)) {
            // Get products
            $productList = Pi::api('product', 'shop')->getListFromIdLight($saleId['product']);
            $categoryList = Pi::api('category', 'shop')->getListFromId($saleId['category']);
            // Get sale
            $order = array('id DESC', 'time_publish DESC');
            $select = $this->getModel('sale')->select()->order($order);
            $saleSet = $this->getModel('sale')->selectWith($select);
            // Make sale list
            foreach ($saleSet as $sale) {
                $saleList[$sale->type][$sale->id] = $sale->toArray();
                if ($sale->type == 'product') {
                    $saleList[$sale->type][$sale->id]['productInfo'] = $productList[$sale->product];
                } elseif ($sale->type == 'category') {
                    $saleList[$sale->type][$sale->id]['categoryInfo'] = $categoryList[$sale->category];
                }
                $saleList[$sale->type][$sale->id]['time'] = sprintf(__('From %s to %s'),
                    _date($sale->time_publish, array('pattern' => 'yyyy-MM-dd HH:mm')),
                    _date($sale->time_expire, array('pattern' => 'yyyy-MM-dd HH:mm')));
                $saleList[$sale->type][$sale->id]['isExpire'] = (time() > $sale->time_expire) ? 1 : 0;
            }
        }
        // Set view
        $this->view()->setTemplate('sale-index');
        $this->view()->assign('sales', $saleList);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $part = $this->params('part');
        // Set option
        $option = array();
        $option['type'] = $id ? 'edit' : 'add';
        $option['part'] = $part;
        // Set form
        $form = new SaleForm('sale', $option);
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new SaleFilter($option));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set time
                $values['time_publish'] = strtotime($values['time_publish']);
                $values['time_expire'] = strtotime($values['time_expire']);
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('sale')->find($values['id']);
                } else {
                    $values['type'] = $part;
                    $row = $this->getModel('sale')->createRow();
                }
                $row->assign($values);
                $row->save();
                // update registry
                Pi::registry('saleInformation', 'shop')->clear();
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
                $values['time_publish'] = date("Y-m-d H:i:s", $values['time_publish']);
                $values['time_expire'] = date("Y-m-d H:i:s", $values['time_expire']);
            } else {
                $values = array();
                $values['time_publish'] = date("Y-m-d H:i:s", time());
                $values['time_expire'] = date("Y-m-d H:i:s", strtotime("+1 week"));
            }
            $form->setData($values);
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
            // update registry
            Pi::registry('saleInformation', 'shop')->clear();
            // jump
            $this->jump(array('action' => 'index'), __('Selected sale delete'));
        }
        $this->jump(array('action' => 'index'), __('Please select sale'));
    }
}