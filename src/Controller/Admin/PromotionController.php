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

use Module\Shop\Form\PromotionFilter;
use Module\Shop\Form\PromotionForm;
use Pi;
use Pi\Filter;
use Pi\Mvc\Controller\ActionController;

class PromotionController extends ActionController
{
    public function indexAction()
    {
        // Get info
        $list = [];
        $order = ['id DESC'];
        $select = $this->getModel('promotion')->select()->order($order);
        $rowset = $this->getModel('promotion')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
            $list[$row->id]['time'] = sprintf(__('From %s to %s'),
                _date($row->time_publish, ['pattern' => 'yyyy-MM-dd HH:mm']),
                _date($row->time_expire, ['pattern' => 'yyyy-MM-dd HH:mm']));
            $list[$row->id]['isExpire'] = (time() > $row->time_expire) ? 1 : 0;
        }
        // Set view
        $this->view()->setTemplate('promotion-index');
        $this->view()->assign('list', $list);
    }

    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        // Set form
        $form = new PromotionForm('promotion');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            // Set slug
            $filter = new Filter\Slug;
            $data['code'] = $filter($data['code']);
            // Form filter
            $form->setInputFilter(new PromotionFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Set time
                $values['time_publish'] = strtotime($values['time_publish']);
                $values['time_expire'] = strtotime($values['time_expire']);
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('promotion')->find($values['id']);
                } else {
                    $row = $this->getModel('promotion')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Add log
                $operation = (empty($values['id'])) ? 'add' : 'edit';
                Pi::api('log', 'shop')->addLog('promotion', $row->id, $operation);
                $message = __('Promotion data saved successfully.');
                $this->jump(['action' => 'index'], $message);
            }
        } else {
            if ($id) {
                $values = $this->getModel('promotion')->find($id)->toArray();
                $values['time_publish'] = date("Y-m-d H:i:s", $values['time_publish']);
                $values['time_expire'] = date("Y-m-d H:i:s", $values['time_expire']);

            } else {
                $values = [];
                $values['time_publish'] = date("Y-m-d H:i:s", time());
                $values['time_expire'] = date("Y-m-d H:i:s", strtotime("+1 month"));
            }
            $form->setData($values);
        }
        // Set view
        $this->view()->setTemplate('promotion-update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add promotion'));
    }
}