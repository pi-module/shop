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
namespace Module\Shop\Api;

use Pi;
use Pi\Application\AbstractApi;
use Zend\Json\Json;

/*
 * Pi::api('shop', 'category')->setLink($product, $category, $create, $update, $price, $stock, $status);
 */

class Category extends AbstractApi
{
    /**
     * Set product category to link table
     */
    public function setLink($product, $category, $create, $update, $price, $stock, $status)
    {
        //Remove
        Pi::model('link', $this->getModule())->delete(array('product' => $product));
        // Add
        $allCategory = Json::decode($category);
        foreach ($allCategory as $category) {
            // Set array
            $values['product'] = $product;
            $values['category'] = $category;
            $values['time_create'] = $create;
            $values['time_update'] = $update;
            $values['price'] = $price;
            $values['stock'] = $stock;
            $values['status'] = $status;
            // Save
            $row = Pi::model('link', $this->getModule())->createRow();
            $row->assign($values);
            $row->save();
        }
    }  



}