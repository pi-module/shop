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

namespace Module\Shop\Controller\Front;

use Pi;
use Pi\Mvc\Controller\ActionController;
use Module\Shop\Form\SerialForm;
use Module\Shop\Form\SerialFilter;

class SerialController extends IndexController
{
    public function indexAction()
    {
        // Set result
        $result = array();
        // Set serial form
        $form = new SerialForm('serial');
        // Check post
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new SerialFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                // Check
                $result = Pi::api('serial', 'shop')->checkSerial($values['serial_number']);
                // Set form empty
                $form->setData(array(
                    'serial_number' => '',
                ));
            }
        }
        // Set view
        $this->view()->setTemplate('serial-index');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Check product serial number'));
        $this->view()->assign('result', $result);
    }
}