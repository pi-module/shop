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

use Module\Shop\Form\PropertyFilter;
use Module\Shop\Form\PropertyForm;
use Pi;
use Pi\Mvc\Controller\ActionController;

class PropertyController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list   = [];
        $order  = ['order ASC', 'id ASC'];
        $select = $this->getModel('property')->select()->order($order);
        $rowSet = $this->getModel('property')->selectWith($select);
        // Make list
        foreach ($rowSet as $row) {
            $list[$row->id] = $row->toArray();
        }
        // Go to update page if empty
        if (empty($list)) {
            return $this->redirect()->toRoute('', ['action' => 'update']);
        }
        // Set view
        $this->view()->setTemplate('property-index');
        $this->view()->assign('list', $list);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        // Set form
        $form = new PropertyForm('property');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new PropertyFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('property')->find($id);
                } else {
                    $row = $this->getModel('property')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($id)) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('property', $row->id, $operation);
                $message = __('Order property data saved successfully.');
                $this->jump(['action' => 'index'], $message);
            }
        } else {
            if ($id) {
                $position = $this->getModel('property')->find($id)->toArray();
                $form->setData($position);
            }
        }
        // Set view
        $this->view()->setTemplate('property-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add property'));
    }
}
