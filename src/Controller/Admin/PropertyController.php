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
use Module\Shop\Form\PropertyForm;
use Module\Shop\Form\PropertyFilter;

class PropertyController extends ActionController
{
    protected $propertyColumns = array(
        'id', 'title', 'order', 'status', 'influence_stock', 'influence_price', 'type'
    );

    public function indexAction()
    {
        // Get info
        $list = array();
        $order = array('order ASC', 'id ASC');
        $select = $this->getModel('property')->select()->order($order);
        $rowset = $this->getModel('property')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        // Go to update page if empty
        if (empty($list)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set view
        $this->view()->setTemplate('property_index');
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
                // Set just category fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->propertyColumns)) {
                        unset($values[$key]);
                    }
                }
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('property')->find($values['id']);
                } else {
                    $row = $this->getModel('property')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('property', $row->id, $operation);
                $message = __('Order property data saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            }  
        } else {
            if ($id) {
            	$position = $this->getModel('property')->find($id)->toArray();
                $form->setData($position);
            }
        }
        // Set view
        $this->view()->setTemplate('property_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add property'));
    }
}