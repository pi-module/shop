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

use Module\Shop\Form\DiscountFilter;
use Module\Shop\Form\DiscountForm;
use Pi;
use Pi\Mvc\Controller\ActionController;

class DiscountController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list   = [];
        $order  = ['id DESC'];
        $select = $this->getModel('discount')->select()->order($order);
        $rowSet = $this->getModel('discount')->selectWith($select);
        // Make list
        foreach ($rowSet as $row) {
            $list[$row->id] = $row->toArray();
        }
        // Set view
        $this->view()->setTemplate('discount-index');
        $this->view()->assign('list', $list);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        // Set form
        $form = new DiscountForm('discount');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new DiscountFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('discount')->find($id);
                } else {
                    $row = $this->getModel('discount')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($id)) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('discount', $row->id, $operation);
                // Clear registry
                Pi::registry('discountList', 'shop')->clear();
                // Check it save or not
                $message = __('Discount data saved successfully.');
                $this->jump(['action' => 'index'], $message);
            }
        } else {
            if ($id) {
                $discount = $this->getModel('discount')->find($id)->toArray();
                $form->setData($discount);
            }
        }
        // Set view
        $this->view()->setTemplate('discount-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add discount'));
    }
}