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

use Module\Shop\Form\AttributeFilter;
use Module\Shop\Form\AttributeForm;
use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;

class AttributeController extends ActionController
{
    public function indexAction()
    {
        // Get position list
        $position = Pi::api('attribute', 'shop')->attributePositionForm();
        // Get info
        $select = $this->getModel('field')->select()->order(['order ASC']);
        $rowSet = $this->getModel('field')->selectWith($select);
        // Make list
        foreach ($rowSet as $row) {
            $field[$row->position][$row->id]                  = $row->toArray();
            $field[$row->position][$row->id]['position_view'] = $position[$row->position];
        }
        // Set view
        $this->view()->setTemplate('attribute-index');
        $this->view()->assign('fields', $field);
        $this->view()->assign('positions', $position);
    }

    /**
     * Attribute Action
     */
    public function updateAction()
    {
        // Get id
        $id   = $this->params('id');
        $type = $this->params('type');

        // check type
        if (!in_array($type, ['text', 'link', 'currency', 'date', 'number', 'select', 'video', 'audio', 'file', 'checkbox'])) {
            $message = __('Attribute field type not set.');
            $url     = ['action' => 'index'];
            $this->jump($url, $message);
        }

        if ($id) {
            $attribute             = $this->getModel('field')->find($id)->toArray();
            $attribute['category'] = Pi::api('attribute', 'shop')->getCategory($attribute['id']);

            // Set value
            $value                    = json_decode($attribute['value'], true);
            $attribute['data']        = $value['data'];
            $attribute['default']     = $value['default'];
            $attribute['information'] = $value['information'];
        }

        // Set option
        $options = [
            'type' => $type,
            'id'   => $id,
        ];

        // Set form
        $form = new AttributeForm('attribute', $options);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            // Set name
            $filter       = new Filter\Slug;
            $data['name'] = $filter($data['name']);
            // Form filter
            $form->setInputFilter(new AttributeFilter($options));
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();

                // Set value
                $value           = [
                    'data'        => (isset($data['data'])) ? $data['data'] : '',
                    'default'     => (isset($data['default'])) ? $data['default'] : '',
                    'information' => $data['information'],
                ];
                $values['value'] = json_encode($value);

                // Set type
                $values['type'] = (!isset($values['type']) || empty($values['type'])) ? $type : $values['type'];

                // Set order
                if (empty($id)) {
                    $columns         = ['order'];
                    $order           = ['order DESC'];
                    $select          = $this->getModel('field')->select()->columns($columns)->order($order)->limit(1);
                    $values['order'] = $this->getModel('field')->selectWith($select)->current()->order + 1;
                }

                // Save values
                if (!empty($id)) {
                    $row = $this->getModel('field')->find($id);
                } else {
                    $row = $this->getModel('field')->createRow();
                }
                $row->assign($values);
                $row->save();

                //
                Pi::api('attribute', 'shop')->setCategory($row->id, $data['category']);

                // Add log
                $operation = (empty($id)) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('attribute', $row->id, $operation);

                // Check it save or not
                $message = __('Attribute field data saved successfully.');
                $url     = ['action' => 'index'];
                $this->jump($url, $message);
            }
        } else {
            if ($id) {
                $form->setData($attribute);
            }
        }
        // Set view
        $this->view()->setTemplate('attribute-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', sprintf(__('Add attribute - type : %s'), $type));
    }

    public function sortAction()
    {
        $order = 1;
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            foreach ($data['mod'] as $id) {
                if ($id > 0) {
                    $row        = $this->getModel('field')->find($id);
                    $row->order = $order;
                    $row->save();
                    $order++;
                }
            }
        }
        // Set view
        $this->view()->setTemplate(false);
    }

    public function deleteAction()
    {
        // Get information
        $this->view()->setTemplate(false);
        $id  = $this->params('id');
        $row = $this->getModel('field')->find($id);
        if ($row) {
            // Remove all data
            $this->getModel('field_data')->delete(['field' => $row->id]);
            // Remove field
            $row->delete();
            $this->jump(['action' => 'index'], __('Selected field delete'));
        } else {
            $this->jump(['action' => 'index'], __('Please select field'));
        }
    }
}
