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
use Module\Shop\Form\CategoryForm;
use Module\Shop\Form\CategoryFilter;

class CategoryController extends ActionController
{
    /**
     * Image Prefix
     */
    protected $ImagePrefix = 'category_';

    /**
     * Category Columns
     */
    protected $categoryColumns = array(
    	'id', 'parent', 'title', 'slug', 'image', 'path', 'description','description_footer',
    	'time_create', 'time_update', 'seo_title', 'seo_keywords', 'seo_description', 'setting', 'status'
    );

    /**
     * index Action
     */
	public function indexAction()
    {
        // Get page
        $page = $this->params('p', 1);
        $module = $this->params('module');
        $list = array();
        // Get info
        $columns = array('id', 'title', 'slug', 'status');
        $order = array('id DESC', 'time_create DESC');
        $select = $this->getModel('category')->select()->columns($columns)->order($order);
        $rowset = $this->getModel('category')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $list[$row->id] = $row->toArray();
        }
        // Go to update page if empty
        if (empty($list)) {
            return $this->redirect()->toRoute('', array('action' => 'update'));
        }
        // Set view
        $this->view()->setTemplate('category_index');
        $this->view()->assign('list', $list);
    }

    /**
     * update Action
     */
    public function updateAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        // Find category
        if ($id) {
            $category = $this->getModel('category')->find($id)->toArray();
        }
        // Set form
        $form = new CategoryForm('category');
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
        	$data = $this->request->getPost();
            $file = $this->request->getFiles();
            // Set slug
            $slug = ($data['slug']) ? $data['slug'] : $data['title'];
            $data['slug'] = Pi::api('shop', 'text')->slug($slug);
            // Form filter
            $form->setInputFilter(new CategoryFilter);
            $form->setData($data);
            if ($form->isValid()) {
            	$values = $form->getData();
            	//
            	foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->categoryColumns)) {
                        unset($values[$key]);
                    }
                }
                // Set seo_title
                $title = ($values['seo_title']) ? $values['seo_title'] : $values['title'];
                $values['seo_title'] = Pi::api('shop', 'text')->title($title);
                // Set seo_keywords
                $keywords = ($values['seo_keywords']) ? $values['seo_keywords'] : $values['title'];
                $values['seo_keywords'] = Pi::api('shop', 'text')->keywords($keywords);
                // Set seo_description
                $description = ($values['seo_description']) ? $values['seo_description'] : $values['title'];
                $values['seo_description'] = Pi::api('shop', 'text')->description($description);
                // Set time
                if (empty($values['id'])) {
                    $values['time_create'] = time();
                }
                $values['time_update'] = time();
                // Save values
                if (!empty($values['id'])) {
                    $row = $this->getModel('category')->find($values['id']);
                } else {
                    $row = $this->getModel('category')->createRow();
                }
                $row->assign($values);
                $row->save();
                // Check it save or not
                if ($row->id) {
                	// Set topic as page for dress up block 
                	if(empty($values['id'])) {
	                	$this->setPage($row->slug, $row->title);
                	} else {	
                	  	$this->updatePage($category['slug'], $row->slug, $row->title);
                    }
                    Pi::service('registry')->page->clear($this->getModule());
                    $message = __('Category data saved successfully.');
                    $this->jump(array('action' => 'index'), $message);
                } else {
                    $message = __('Category data not saved.');
                }
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }	
        } else {
            if ($id) {
                $form->setData($category);
                $message = 'You can edit this category';
            } else {
                $message = 'You can add new category';
            }
        }
        // Set view
        $this->view()->setTemplate('category_update');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Add a category'));
        $this->view()->assign('message', $message);
    }

    /**
     * Add page settings to system
     *
     * @author Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
     * @param string $name
     * @param string $title
     * @return int
     */
    protected function setPage($name, $title)
    {
        $page = array(
            'section'       => 'front',
            'module'        => $this->getModule(),
            'controller'    => 'topic',
            'action'        => $name,
            'title'         => $title,
            'block'         => 1,
            'custom'        => 0,
        );
        $row = Pi::model('page')->createRow($page);
        $row->save();
        return $row->id;
    }

    /**
     * Remove from system page settings
     *
     * @author Taiwen Jiang <taiwenjiang@tsinghua.org.cn>
     * @param stinr $name
     * @return int
     */
    protected function removePage($name)
    {
        $where = array(
            'section'       => 'front',
            'module'        => $this->getModule(),
            'controller'    => 'topic',
            'action'        => $name,
        );
        $count = Pi::model('page')->delete($where);
        return $count;
    }
    
    /**
     * Update from system page settings
     *
     * @param stinr $name
     * @return int
     */
    protected function updatePage($old_action, $new_action, $new_title)
    {
        $where = array(
            'section'       => 'front',
            'module'        => $this->getModule(),
            'controller'    => 'topic',
            'action'        => $old_action,
        );
        $count = Pi::model('page')->update(array('action' => $new_action, 'title' => $new_title), $where);
        return $count;
    }
}