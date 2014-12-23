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
use Pi\File\Transfer\Upload;
use Module\Shop\Form\AttributeForm;
use Module\Shop\Form\AttributeFilter;

class AttributeController extends ActionController
{
    /**
     * Attribute Image Prefix
     */
    protected $ImageAttributePrefix = 'attribute_';

    /**
     * Attribute Columns
     */
    protected $attributeColumns = array(
        'id', 'title', 'image', 'type', 'order', 'status', 'search', 'value'
    );

    /**
     * Attribute Action
     */
    public function indexAction()
    {
        // Get info
        $select = $this->getModel('field')->select()->order(array('order ASC'));
        $rowset = $this->getModel('field')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $field[$row->id] = $row->toArray();
            $field[$row->id]['imageUrl'] = Pi::url(sprintf('upload/%s/icon/%s', $this->config('file_path'), $field[$row->id]['image']));
        }
        // Go to update page if empty
        if (empty($field)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set view
        $this->view()->setTemplate('attribute_index');
        $this->view()->assign('fields', $field);
    }

    /**
     * Attribute Action
     */
    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Get attribute
        if ($id) {
            $attribute = $this->getModel('field')->find($id)->toArray();
        }
        // Set form
        $form = new AttributeForm('attribute', $options);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $file = $this->request->getFiles();
            $form->setInputFilter(new AttributeFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // upload image
                if (!empty($file['image']['name'])) {
                    // Set upload path
                    $path = Pi::path(sprintf('upload/%s/icon', $this->config('file_path')));
                    // Upload
                    $uploader = new Upload;
                    $uploader->setDestination($path);
                    $uploader->setRename($this->ImageAttributePrefix . '%random%');
                    $uploader->setExtension($this->config('image_extension'));
                    $uploader->setSize($this->config('image_size'));
                    if ($uploader->isValid()) {
                        $uploader->receive();
                        // Get image name
                        $values['image'] = $uploader->getUploaded('image');
                    } else {
                        $this->jump(array('action' => 'update'), __('Problem in upload image. please try again'));
                    }
                } elseif (!isset($values['image'])) {
                    $values['image'] = '';  
                }
                // Set just product fields
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->attributeColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set order
                $select = $this->getModel('field')->select()->columns(array('order'))->order(array('order DESC'))->limit(1);
                $values['order'] = $this->getModel('field')->selectWith($select)->current()->order + 1;
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('field')->find($values['id']);
                } else {
                    $row = $this->getModel('field')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('attribute', $row->id, $operation);
                // Check it save or not
                $message = __('Attribute field data saved successfully.');
                $url = array('action' => 'index');
                $this->jump($url, $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        } else {
            if ($id) {
                $form->setData($attribute);
            }
        }
        // Set view
        $this->view()->setTemplate('attribute_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add attribute'));
    }

    public function sortAction()
    {
        $order = 1;
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            foreach ($data['mod'] as $id) {
                if ($id > 0) {
                    $row = $this->getModel('field')->find($id);
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
        $id = $this->params('id');
        $row = $this->getModel('field')->find($id);
        if ($row) {
            // Remove all data
            $this->getModel('field_data')->delete(array('field' => $row->id));
            // Remove field
            $row->delete();
            $this->jump(array('action' => 'index'), __('Selected field delete'));
        } else {
            $this->jump(array('action' => 'index'), __('Please select field'));
        }
    }
}