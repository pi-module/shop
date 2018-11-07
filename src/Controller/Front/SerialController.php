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

namespace Module\Shop\Controller\Front;

use Module\Shop\Form\SerialFilter;
use Module\Shop\Form\SerialForm;
use Pi;
use Pi\Mvc\Controller\ActionController;

class SerialController extends IndexController
{
    public function indexAction()
    {
        // Set result
        $result = [];

        // Save statistics
        if (Pi::service('module')->isActive('statistics')) {
            Pi::api('log', 'statistics')->save('shop', 'serial');
        }

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
                $form->setData(
                    [
                        'serial_number' => '',
                    ]
                );
            }
        }
        // Set view
        $this->view()->setTemplate('serial-index');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Check product serial number'));
        $this->view()->assign('result', $result);
    }
}