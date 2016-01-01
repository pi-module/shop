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
use Pi\Paginator\Paginator;
use Pi\File\Transfer\Upload;
use Module\Shop\Form\AttachForm;
use Module\Shop\Form\AttachFilter;
use Zend\Json\Json;

class AttachController extends ActionController
{
    protected $AttachPrefix = 'attach_';

    protected $attachColumns = array(
        'id', 'title', 'file', 'path', 'product', 'time_create', 'size', 'type', 'status', 'hits'
    );

    public function indexAction()
    {
        // Get page
        $page = $this->params('page', 1);
        $product = $this->params('product');
        $module = $this->params('module');
        // Set info
        $order = array('time_create DESC', 'id DESC');
        $offset = (int)($page - 1) * $this->config('admin_perpage');
        $limit = intval($this->config('admin_perpage'));
        // Get info
        $select = $this->getModel('attach')->select()->order($order)->offset($offset)->limit($limit);
        $rowset = $this->getModel('attach')->selectWith($select);
        // Make list
        foreach ($rowset as $row) {
            $file[$row->id] = $row->toArray();
            $product = $this->getModel('product')->find($file[$row->id]['product'])->toArray();
            $file[$row->id]['time_create'] = _date($file[$row->id]['time_create']);
            $file[$row->id]['productTitle'] = $product['title'];
            $file[$row->id]['preview'] = $this->filePreview($file[$row->id]['type'], $file[$row->id]['path'], $file[$row->id]['file']);
            if ($file[$row->id]['type'] == 'image') {
                $file[$row->id]['link'] = Pi::url('upload/' . $this->config('image_path') . '/original/' . $file[$row->id]['path'] . '/' . $file[$row->id]['file']);
            } else {
                $file[$row->id]['link'] = Pi::url('upload/' . $this->config('file_path') . '/' . $file[$row->id]['type'] . '/' . $file[$row->id]['path'] . '/' . $file[$row->id]['file']);
            }
        }
        // Set paginator
        $columns = array('count' => new \Zend\Db\Sql\Predicate\Expression('count(*)'));
        $select = $this->getModel('attach')->select()->columns($columns);
        $count = $this->getModel('attach')->selectWith($select)->current()->count;
        $paginator = Paginator::factory(intval($count));
        $paginator->setItemCountPerPage($this->config('admin_perpage'));
        $paginator->setCurrentPageNumber($page);
        $paginator->setUrlOptions(array(
            'router' => $this->getEvent()->getRouter(),
            'route' => $this->getEvent()->getRouteMatch()->getMatchedRouteName(),
            'params' => array_filter(array(
                'module' => $this->getModule(),
                'controller' => 'attach',
                'action' => 'index',
            )),
        ));
        // Set view
        $this->view()->setTemplate('attach-index');
        $this->view()->assign('files', $file);
        $this->view()->assign('paginator', $paginator);
    }

    public function addAction()
    {
        // Get id
        $id = $this->params('id');
        if (empty($id)) {
            $this->jump(array('controller' => 'product', 'action' => 'index'), __('You must select product'));
        }
        // Get product
        $product = $this->getModel('product')->find($id)->toArray();
        if (empty($product)) {
            $this->jump(array('controller' => 'product', 'action' => 'index'), __('Your selected product not exist'));
        }
        // Get all attach files
        $select = $this->getModel('attach')->select()->where(array('product' => $product['id']));
        $rowset = $this->getModel('attach')->selectWith($select);
        // Make list
        $contents = array();
        foreach ($rowset as $row) {
            $content[$row->id] = $row->toArray();
            $content[$row->id]['time_create'] = _date($content[$row->id]['time_create']);
            $content[$row->id]['preview'] = $this->filePreview($content[$row->id]['type'], $content[$row->id]['path'], $content[$row->id]['file']);
            $contents[] = $content[$row->id];
        }
        // set nav
        $nav = array(
            'page' => 'attach',
            'type' => 'edit',
        );
        // Set view
        $this->view()->setTemplate('attach-add');
        $this->view()->assign('content', Json::encode($contents));
        $this->view()->assign('product', $product);
        $this->view()->assign('title', sprintf(__('Attach files to %s'), $product['title']));
        $this->view()->assign('nav', $nav);
    }

    public function editAction()
    {
        // Get id
        $id = $this->params('id');
        $module = $this->params('module');
        if (empty($id)) {
            $this->jump(array('action' => 'index'), __('You must select file'));
        }
        //Get file and product
        $file = $this->getModel('attach')->find($id)->toArray();
        $file['view'] = $this->fileView($file['type'], $file['path'], $file['file']);
        $product = $this->getModel('product')->find($file['product'])->toArray();
        // Set form
        $form = new AttachForm('attach', $product['id']);
        $form->setAttribute('enctype', 'multipart/form-data');
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $form->setInputFilter(new AttachFilter);
            $form->setData($data);
            if ($form->isValid()) {
                $values = $form->getData();
                foreach (array_keys($values) as $key) {
                    if (!in_array($key, $this->attachColumns)) {
                        unset($values[$key]);
                    }
                }
                $row = $this->getModel('attach')->find($values['id']);
                $row->assign($values);
                $row->save();
                $message = __('All changes in file saved successfully.');
                $this->jump(array('action' => 'index'), $message);
            } else {
                $message = __('Invalid data, please check and re-submit.');
            }
        } else {
            $form->setData($file);
            $message = 'You can edit this File';
        }
        // Set view
        $this->view()->setTemplate('attach-edit');
        $this->view()->assign('form', $form);
        $this->view()->assign('title', __('Edit file'));
        $this->view()->assign('message', $message);
        $this->view()->assign('file', $file);
        $this->view()->assign('product', $product);
    }

    public function uploadAction()
    {
        // deactive log
        Pi::service('log')->active(false);
        // Set return
        $return = array(
            'status' => 1,
            'message' => '',
            'id' => '',
            'title' => '',
            'time_create' => '',
            'type' => '',
            'status' => '',
            'hits' => '',
            'size' => '',
            'preview' => '',
        );
        // Get id
        $id = $this->params('id');
        if (empty($id)) {
            $return = array(
                'status' => 0,
                'message' => __('You must select product'),
            );
        } else {
            // Get product
            $product = $this->getModel('product')->find($id)->toArray();
            if (empty($product)) {
                $return = array(
                    'status' => 0,
                    'message' => __('Your selected product not exist'),
                );
            } else {
                // start upload
                $path = sprintf('%s/%s', date('Y'), date('m'));
                $destination = Pi::path(sprintf('upload/%s/file/%s', $this->config('file_path'), $path));
                // Get file type
                $file = $this->request->getFiles();
                $type = $this->fileType($file['file']['name']);
                if ($type == 'image') {
                    $fileName = Pi::api('image', 'shop')->rename($file['file']['name'], $this->AttachPrefix, $path);
                } else {
                    $fileName = $this->AttachPrefix . '%random%';
                }
                // Upload
                $uploader = new Upload;
                $uploader->setDestination($destination);
                $uploader->setRename($fileName);
                $uploader->setExtension($this->config('file_extension'));
                $uploader->setSize($this->config('file_size'));
                if ($uploader->isValid()) {
                    $uploader->receive();
                    // Set info
                    $file = $uploader->getUploaded('file');
                    $title = $this->fileTitle($product['title'], $file);
                    $this->filePath($type, $path, $file);
                    // Set save array
                    $values['file'] = $file;
                    $values['title'] = $title;
                    $values['path'] = $path;
                    $values['product'] = $product['id'];
                    $values['time_create'] = time();
                    $values['type'] = $type;
                    $values['status'] = 1;
                    $values['size'] = '';
                    // save in DB
                    $row = $this->getModel('attach')->createRow();
                    $row->assign($values);
                    $row->save();
                    // Set erturn array
                    $return['id'] = $row->id;
                    $return['title'] = $row->title;
                    $return['time_create'] = _date($row->time_create);
                    $return['type'] = $row->type;
                    $return['status'] = $row->status;
                    $return['size'] = $row->size;
                    $return['preview'] = $this->filePreview($row->type, $row->path, $row->file);
                    // Set product Attach count
                    Pi::api('product', 'shop')->attachCount($id);
                } else {
                    // Upload error
                    $messages = $uploader->getMessages();
                    $return = array(
                        'status' => 0,
                        'message' => implode('; ', $messages),
                    );
                }
            }
        }
        $this->view()->setTemplate(false)->setLayout('layout-content');
        return Json::encode($return);
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        if ($id) {
            $row = $this->getModel('attach')->find($id);
            $this->removeFile($row->file, $row->type, $row->path);
            $row->delete();
            $ajaxstatus = 1;
            $message = __('Your attached file remove successfully');
            $id = $row->id;
            Pi::api('product', 'shop')->attachCount($row->product);
        } else {
            $ajaxstatus = 0;
            $message = __('Please select file');
            $id = 0;
        }

        return array(
            'status' => $ajaxstatus,
            'message' => $message,
            'id' => $id,
        );
    }

    protected function fileTitle($title, $file)
    {
        $file = pathinfo($file, PATHINFO_FILENAME);
        $file = array_filter(explode('-', $file));
        $file = implode(' ', $file);
        return sprintf('%s %s', $title, $file);
    }

    protected function fileType($file)
    {
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        $extension = strtolower($extension);
        switch ($extension) {
            case 'zip':
            case 'rar':
            case 'tar':
                $type = 'archive';
                break;

            case 'jpg':
            case 'jpeg':
            case 'png':
            case 'gif':
                $type = 'image';
                break;

            case 'avi':
            case 'flv':
            case 'mp4':
            case 'webm':
            case 'ogv':
                $type = 'video';
                break;

            case 'mp3':
            case 'ogg':
                $type = 'audio';
                break;

            case 'pdf':
                $type = 'pdf';
                break;

            case 'doc':
            case 'docx':
                $type = 'doc';
                break;

            default:
                $type = 'other';
                break;
        }
        // return
        return $type;
    }

    protected function filePath($type, $path, $file)
    {
        $oldPath = Pi::path(sprintf('upload/%s/file/%s/%s', $this->config('file_path'), $path, $file));
        switch ($type) {
            // Move and resize images
            case 'image':
                $newPach = Pi::path(sprintf('upload/%s/original/%s/%s', $this->config('image_path'), $path, $file));
                // Copy image to new path
                Pi::service('file')->copy($oldPath, $newPach);
                Pi::service('file')->remove($oldPath);
                // process image
                Pi::api('image', 'shop')->process($file, $path);
                break;

            // Move video, audio, pdf, doc
            case 'video':
            case 'audio':
            case 'pdf':
            case 'doc':
                $newPach = Pi::path(sprintf('upload/%s/%s/%s/%s', $this->config('file_path'), $type, $path, $file));
                Pi::service('file')->copy($oldPath, $newPach);
                Pi::service('file')->remove($oldPath);
                break;
        }
    }

    protected function filePreview($type, $path, $file)
    {
        if ($type == 'image') {
            $image = sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $path, $file);
            $preview = Pi::url($image);
        } else {
            $image = sprintf('image/%s.png', $type);
            $preview = Pi::service('asset')->getModuleAsset($image, $this->getModule());
        }
        return $preview;
    }

    protected function fileView($type, $path, $file)
    {
        if ($type == 'image') {
            $file = sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $path, $file);
            $view = Pi::url($file);
        } else {
            $file = sprintf('upload/%s/%s/%s/%s', $this->config('file_path'), $type, $path, $file);
            $view = Pi::url($file);
        }
        return $view;
    }

    protected function removeFile($file, $type, $path)
    {
        /* if ($type == 'image') {
            $remove = array(
                Pi::path(sprintf('upload/%s/original/%s/%s', $this->config('image_path'), $path, $file)),
                Pi::path(sprintf('upload/%s/large/%s/%s', $this->config('image_path'), $path, $file)),
                Pi::path(sprintf('upload/%s/medium/%s/%s', $this->config('image_path'), $path, $file)),
                Pi::path(sprintf('upload/%s/thumb/%s/%s', $this->config('image_path'), $path, $file)),
            );
        } else {
            $remove = Pi::path(sprintf('upload/%s/%s/%s/%s', $this->config('file_path'), $type, $path, $file));
        }
        Pi::service('file')->remove($remove); */
    }
}