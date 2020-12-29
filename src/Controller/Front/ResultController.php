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

use Pi;
use Pi\Mvc\Controller\ActionController;

class ResultController extends ActionController
{
    public function indexAction()
    {
        // Get info from url
        $module = $this->params('module');
        // Get config
        $config = Pi::service('registry')->config->read($module);
        // Check config
        if ($config['homepage_type'] != 'brand') {
            $this->getResponse()->setStatusCode(404);
            $this->terminate('', '', 'error-404');
            $this->view()->setLayout('layout-simple');
            return;
        }
        // category list
        $categoriesJson = Pi::api('category', 'shop')->categoryListJson();
        // Set view
        $this->view()->setTemplate('product-angular');
        $this->view()->assign('config', $config);
        $this->view()->assign('categoriesJson', $categoriesJson);
        $this->view()->assign('pageType', 'all');
    }
}
