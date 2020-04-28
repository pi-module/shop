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

namespace Module\Shop\Api;

use Pi;
use Pi\Application\Api\AbstractApi;
use Zend\Db\Sql\Predicate\Expression;

/*
 * Pi::api('product', 'shop')->getProduct($parameter, $type);
 * Pi::api('product', 'shop')->getProductLight($parameter, $type);
 * Pi::api('product', 'shop')->getProductOrder($id);
 * Pi::api('product', 'shop')->getBrandCount($brand);
 * Pi::api('product', 'shop')->getBrandHasNew($brand);
 * Pi::api('product', 'shop')->getCategoryMainCount($category);
 * Pi::api('product', 'shop')->getCategoryLinkCount($category);
 * Pi::api('product', 'shop')->getListFromId($id);
 * Pi::api('product', 'shop')->getListFromIdLight($id);
 * Pi::api('product', 'shop')->getCompareList($slugList, $mainProduct);
 * Pi::api('product', 'shop')->searchRelated($title, $type);
 * Pi::api('product', 'shop')->attributeCount($id);
 * Pi::api('product', 'shop')->attachCount($id);
 * Pi::api('product', 'shop')->relatedCount($id);
 * Pi::api('product', 'shop')->AttachList($id);
 * Pi::api('product', 'shop')->FavoriteList();
 * Pi::api('product', 'shop')->canonizePriceAndDiscount($product, $config);
 * Pi::api('product', 'shop')->canonizeProduct($product, $categoryList);
 * Pi::api('product', 'shop')->canonizeProductLight($product);
 * Pi::api('product', 'shop')->canonizeProductOrder($product);
 * Pi::api('product', 'shop')->canonizeProductJson($product);
 * Pi::api('product', 'shop')->sitemap();
 * Pi::api('product', 'shop')->migrateMedia();
 */

class Product extends AbstractApi
{
    public function getProduct($parameter, $type = 'id')
    {
        // Get product
        $product = Pi::model('product', $this->getModule())->find($parameter, $type);
        $product = $this->canonizeProduct($product);
        return $product;
    }

    public function getProductLight($parameter, $type = 'id')
    {
        // Get product
        $product = Pi::model('product', $this->getModule())->find($parameter, $type);
        $product = $this->canonizeProductLight($product);
        return $product;
    }

    public function getProductOrder($id)
    {
        // Get product
        $product = Pi::model('product', $this->getModule())->find($id);
        $product = $this->canonizeProductOrder($product);
        return $product;
    }

    public function getBrandCount($brand)
    {
        $where   = ['brand' => $brand];
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('product', $this->getModule())->select()->columns($columns)->where($where);
        return Pi::model('product', $this->getModule())->selectWith($select)->current()->count;
    }

    public function getBrandHasNew($brand)
    {
        // Get config
        $config  = Pi::service('registry')->config->read($this->getModule());
        $time    = time() - ($config['new_product'] * 86400);
        $where   = ['brand' => $brand, 'time_create > ?' => $time];
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('product', $this->getModule())->select()->columns($columns)->where($where);
        return Pi::model('product', $this->getModule())->selectWith($select)->current()->count;
    }

    public function getCategoryMainCount($category)
    {
        $where   = ['category_main' => $category];
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('product', $this->getModule())->select()->columns($columns)->where($where);
        return Pi::model('product', $this->getModule())->selectWith($select)->current()->count;
    }

    public function getCategoryLinkCount($category)
    {
        $where   = ['category' => $category];
        $columns = ['count' => new Expression('count(DISTINCT `product`)')];
        $select  = Pi::model('link', $this->getModule())->select()->columns($columns)->where($where);
        return Pi::model('link', $this->getModule())->selectWith($select)->current()->count;
    }

    public function searchRelated($title, $type)
    {
        $list = [];
        switch ($type) {
            case 1:
                $where = ['title LIKE ?' => '%' . $title . '%'];
                break;

            case 2:
                $where = ['title LIKE ?' => $title . '%'];
                break;

            case 3:
                $where = ['title LIKE ?' => '%' . $title];
                break;

            case 4:
                $where = ['title' => $title];
                break;
        }
        $columns = ['id'];
        $select  = Pi::model('product', $this->getModule())->select()->where($where)->columns($columns);
        $rowSet  = Pi::model('product', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[] = $row->id;
        }
        return $list;
    }

    public function getListFromId($id, $limit = 0)
    {
        $list   = [];
        $where  = ['id' => $id, 'status' => 1];
        $select = Pi::model('product', $this->getModule())->select()->where($where);
        if ($limit > 0) {
            $select->limit($limit);
        }
        $rowSet = Pi::model('product', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $this->canonizeProduct($row);
        }
        return $list;
    }

    public function getListFromIdLight($id, $limit = 0)
    {
        $list   = [];
        $where  = ['id' => $id, 'status' => 1];
        $select = Pi::model('product', $this->getModule())->select()->where($where);
        if ($limit > 0) {
            $select->limit($limit);
        }
        $rowSet = Pi::model('product', $this->getModule())->selectWith($select);
        foreach ($rowSet as $row) {
            $list[$row->id] = $this->canonizeProductLight($row);
        }
        return $list;
    }

    public function getCompareList($slugList, $mainProduct)
    {
        $list   = [];
        $where  = ['slug' => $slugList, 'status' => 1, /*'category_main' => $mainProduct['category_main']*/];
        $select = Pi::model('product', $this->getModule())->select()->where($where);
        $rowSet = Pi::model('product', $this->getModule())->selectWith($select);
        foreach ($rowSet as $key => $row) {
            $product = $this->canonizeProduct($row);
            // Set attribute
            if ($row->attribute) {
                $attributes           = Pi::api('attribute', 'shop')->Product($row->id, $row->category_main);
                $product['attribute'] = $attributes['all'];
            }
            $key        = array_search($product['slug'], $slugList);
            $list[$key] = $product;
        }
        return $list;
    }

    public function attributeCount($id)
    {
        // Get attach count
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('field_data', $this->getModule())->select()->columns($columns);
        $count   = Pi::model('field_data', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('product', $this->getModule())->update(['attribute' => $count], ['id' => $id]);
    }

    public function attachCount($id)
    {
        // Get attach count
        $where   = ['product' => $id];
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('attach', $this->getModule())->select()->columns($columns)->where($where);
        $count   = Pi::model('attach', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('product', $this->getModule())->update(['attach' => $count], ['id' => $id]);
    }

    public function relatedCount($id)
    {
        // Get attach count
        $where   = ['product_id' => $id];
        $columns = ['count' => new Expression('count(*)')];
        $select  = Pi::model('related', $this->getModule())->select()->columns($columns)->where($where);
        $count   = Pi::model('related', $this->getModule())->selectWith($select)->current()->count;
        // Set attach count
        Pi::model('product', $this->getModule())->update(['related' => $count], ['id' => $id]);
    }

    public function attachList($id)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Set info
        $file  = [];
        $where = ['product' => $id, 'status' => 1];
        $order = ['time_create DESC', 'id DESC'];

        // Get all attach files
        $select = Pi::model('attach', $this->getModule())->select()->where($where)->order($order);
        $rowSet = Pi::model('attach', $this->getModule())->selectWith($select);

        // Make list
        foreach ($rowSet as $row) {
            $file[$row->type][$row->id]                     = $row->toArray();
            $file[$row->type][$row->id]['time_create_view'] = _date($file[$row->type][$row->id]['time_create']);

            if ($file[$row->type][$row->id]['type'] == 'image') {
                // Set image original url
                $file[$row->type][$row->id]['originalUrl'] = Pi::url(
                    sprintf(
                        'upload/%s/original/%s/%s',
                        $config['image_path'],
                        $file[$row->type][$row->id]['path'],
                        $file[$row->type][$row->id]['file']
                    )
                );
                // Set image large url
                $file[$row->type][$row->id]['largeUrl'] = Pi::url(
                    sprintf(
                        'upload/%s/large/%s/%s',
                        $config['image_path'],
                        $file[$row->type][$row->id]['path'],
                        $file[$row->type][$row->id]['file']
                    )
                );
                // Set image medium url
                $file[$row->type][$row->id]['mediumUrl'] = Pi::url(
                    sprintf(
                        'upload/%s/medium/%s/%s',
                        $config['image_path'],
                        $file[$row->type][$row->id]['path'],
                        $file[$row->type][$row->id]['file']
                    )
                );
                // Set image thumb url
                $file[$row->type][$row->id]['thumbUrl'] = Pi::url(
                    sprintf(
                        'upload/%s/thumb/%s/%s',
                        $config['image_path'],
                        $file[$row->type][$row->id]['path'],
                        $file[$row->type][$row->id]['file']
                    )
                );
            } else {
                $file[$row->type][$row->id]['fileUrl'] = Pi::url(
                    sprintf(
                        'upload/%s/file/%s/%s',
                        $config['file_path'],
                        $file[$row->type][$row->id]['path'],
                        $file[$row->type][$row->id]['file']
                    )
                );
            }
        }

        // return
        return $file;
    }

    public function favoriteList($uid = null)
    {
        // Get user id
        if ($uid == null) {
            $uid = Pi::user()->getId();
        }

        // Check user
        if ($uid > 0) {
            $favoriteIds = Pi::api('favourite', 'favourite')->userFavourite($uid, $this->getModule());
            // Check list of ides
            if (!empty($favoriteIds)) {
                // Get config
                $config = Pi::service('registry')->config->read($this->getModule());
                // Set list
                $list   = [];
                $where  = ['id' => $favoriteIds, 'status' => 1];
                $select = Pi::model('product', $this->getModule())->select()->where($where);
                $rowSet = Pi::model('product', $this->getModule())->selectWith($select);
                foreach ($rowSet as $row) {
                    $story          = [];
                    $story['title'] = $row->title;
                    $story['url']   = Pi::url(
                        Pi::service('url')->assemble(
                            'shop', [
                                'module'     => $this->getModule(),
                                'controller' => 'product',
                                'slug'       => $row->slug,
                            ]
                        )
                    );
                    $story['image'] = '';
                    if ($row->image) {
                        $story['image'] = Pi::url(
                            sprintf(
                                'upload/%s/thumb/%s/%s',
                                $config['image_path'],
                                $row->path,
                                $row->image
                            )
                        );
                    }
                    $list[$row->id] = $story;
                }
                return $list;
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function marketable($product)
    {
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());
        // Get sale id
        $saleExpireId = Pi::api('sale', 'shop')->getInformation('expire');
        // Check sale
        if (in_array($product['category_main'], $saleExpireId['category'])
            && $config['sale_category'] == 'non-marketable'
        ) {
            return 0;
        }
        // check
        switch ($config['order_stock']) {
            case 'never':
                if ($product['price'] > 0) {
                    return 1;
                } else {
                    return 0;
                }
                break;

            case 'manual':
                if ($product['price'] > 0 && $product['stock_type'] == 1) {
                    return 1;
                } elseif ($product['price'] > 0 && $product['stock_type'] == 5) {
                    return 2;
                } else {
                    return 0;
                }
                break;

            case 'product':
                if ($product['price'] > 0 && $product['stock'] > 0) {
                    return 1;
                } else {
                    return 0;
                }
                break;

            case 'property':
                if ($product['price'] > 0) {
                    return 1;
                } else {
                    return 0;
                }
                break;
        }
    }

    public function canonizePriceAndDiscount($product, $config)
    {
        // Get config
        $configSystem = Pi::service('registry')->config->read('system');

        // Set price main
        $product['price_main'] = $product['price'];

        // Check discount
        if ($config['order_discount']) {
            // Get discount percent
            $userDiscount    = [];
            $saleDiscount    = [];
            $uid             = Pi::user()->getId();
            $roles           = Pi::user()->getRole($uid);
            $discounts       = Pi::registry('discountList', 'shop')->read();
            $saleInformation = Pi::registry('saleInformation', 'shop')->read();

            // Check product discounts
            if (!empty($product['setting']['discount'])) {
                foreach ($product['setting']['discount'] as $role => $percent) {
                    if (isset($percent) && $percent > 0 && in_array($role, $roles)) {
                        $userDiscount[] = $percent;
                    }
                }
            }

            // Check module discounts
            if (!empty($discounts)) {
                foreach ($discounts as $discount) {
                    if (in_array($discount['role'], $roles) && in_array($discount['category'], $product['category'])) {
                        $userDiscount[] = $discount['percent'];
                    }
                }
            }

            // Check sale
            if (!empty($saleInformation) && in_array($product['id'], $saleInformation['idActive']['product'])) {
                if ($saleInformation['infoAll']['product'][$product['id']]['time_publish'] < time()
                    && $saleInformation['infoAll']['product'][$product['id']]['time_expire'] > time()
                ) {
                    $userDiscount = [];
                    $saleDiscount = $saleInformation['infoAll']['product'][$product['id']];
                }
            } elseif (!empty($saleInformation) && in_array($product['category_main'], $saleInformation['idActive']['category'])) {
                $userDiscount = [];
                $saleDiscount = $saleInformation['infoAll']['category'][$product['category_main']];
            }

            // Make discount price
            if (!empty($userDiscount)) {
                $userDiscount              = max($userDiscount);
                $price                     = ($product['price'] - ($product['price'] * ($userDiscount / 100)));
                $price                     = Pi::api('api', 'order')->makePrice($price);
                $product['price_discount'] = $product['price'];
                $product['price']          = $price;
            } elseif (!empty($saleDiscount)) {
                switch ($saleDiscount['type']) {
                    case 'product':
                        $price = Pi::api('api', 'order')->makePrice($saleDiscount['price']);
                        break;

                    case 'category':
                        $price = ($product['price'] - ($product['price'] * ($saleDiscount['percent'] / 100)));
                        $price = Pi::api('api', 'order')->makePrice($price);
                        break;
                }
                $product['price_discount'] = $product['price'];
                $product['price']          = $price;
                $product['price_sale']     = 1;
                $product['price_time']     = [
                    'year'   => date("Y", $saleDiscount['time_expire']),
                    'month'  => date("m", $saleDiscount['time_expire']),
                    'day'    => date("d", $saleDiscount['time_expire']),
                    'hour'   => date("H", $saleDiscount['time_expire']),
                    'minute' => date("i", $saleDiscount['time_expire']),
                    'second' => date("s", $saleDiscount['time_expire']),
                ];
            }
        }

        // Set price
        $product['price_view']          = Pi::api('api', 'shop')->viewPrice($product['price']);
        $product['price_discount_view'] = Pi::api('api', 'shop')->viewPrice($product['price_discount']);
        $product['price_currency']      = empty($configSystem['number_currency']) ? 'USD' : $configSystem['number_currency'];

        // Set has discount
        $product['price_discount_has'] = 0;
        if ($product['price_discount'] && ($product['price_discount'] > $product['price'])) {
            $product['price_discount_has'] = 1;
        }

        // Set price_percent
        $product['price_percent'] = 0;
        if ($product['price_discount_has']) {
            $product['price_percent'] = 100 - intval(($product['price'] * 100) / $product['price_main']);
        }

        // return product
        return $product;
    }

    public function canonizeProduct($product, $categoryList = [])
    {
        // Check
        if (empty($product)) {
            return '';
        }
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Get category list
        $categoryList = (empty($categoryList)) ? Pi::registry('categoryList', 'shop')->read() : $categoryList;

        // boject to array
        $product = $product->toArray();

        // Make setting
        $product['setting'] = json_decode($product['setting'], true);

        // Set text_summary
        $product['text_summary']      = Pi::service('markup')->render($product['text_summary'], 'html', 'html');
        $product['text_summary_view'] = Pi::service('markup')->render($product['text_summary'], 'html', 'text', ['nl2br' => true]);

        // Set text_description
        $product['text_description'] = Pi::service('markup')->render($product['text_description'], 'html', 'html');

        // Set times
        $product['time_create_view'] = _date($product['time_create']);
        $product['time_update_view'] = _date($product['time_update']);

        // Set product url
        $product['productUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'product',
                    'slug'       => $product['slug'],
                ]
            )
        );

        // Set cart url
        $product['cartUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'cart',
                    'action'     => 'add',
                    //'slug'          => $product['slug'],
                ]
            )
        );

        // Set category information
        $product['category'] = json_decode($product['category']);
        foreach ($product['category'] as $category) {
            $product['categories'][$category]['id']    = $categoryList[$category]['id'];
            $product['categories'][$category]['title'] = $categoryList[$category]['title'];
            $product['categories'][$category]['url']   = Pi::url(
                Pi::service('url')->assemble(
                    'shop', [
                        'module'     => $this->getModule(),
                        'controller' => 'category',
                        'slug'       => $categoryList[$category]['slug'],
                    ]
                )
            );
        }

        // Set brand information
        if (!empty($product['brand'])) {
            $product['brandTitle'] = $categoryList[$product['brand']]['title'];
            $product['brandUrl']   = Pi::url(
                Pi::service('url')->assemble(
                    'shop', [
                        'module'     => $this->getModule(),
                        'controller' => 'category',
                        'slug'       => $categoryList[$product['brand']]['slug'],
                    ]
                )
            );
        }

        // Set discount
        $product = $this->canonizePriceAndDiscount($product, $config);

        // Set stock
        switch ($product['stock_type']) {
            default:
            case 1:
                $product['stock_type_view'] = __('In stock');
                break;

            case 2:
                $product['stock_type_view'] = __('Out of stock');
                break;

            case 3:
                $product['stock_type_view'] = __('Coming soon');
                break;

            case 4:
                $product['stock_type_view'] = __('Contact');
                break;

            case 5:
                $product['stock_type_view'] = __('Variable stock');
                break;
        }

        // Set marketable
        $product['marketable'] = $this->marketable($product);

        // Set image
        if ($product['main_image']) {
            $product['largeUrl']  = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('large')
            );
            $product['mediumUrl'] = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('medium')
            );
            $product['thumbUrl']  = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('thumbnail')
            );
        } else {
            $product['largeUrl']  = '';
            $product['mediumUrl'] = '';
            $product['thumbUrl']  = '';
        }

        // Set ribbon
        $product['ribbon_class'] = '';
        $saleId                  = Pi::api('sale', 'shop')->getInformation();
        if (in_array($product['id'], $saleId['product'])) {
            $product['ribbon']       = __('On sale');
            $product['ribbon_class'] = 'product-ribbon';
        } elseif (isset($product['price_discount']) && ($product['price_discount'] > $product['price'])) {
            $product['ribbon']       = __('Have discount');
            $product['ribbon_class'] = 'product-ribbon';
        } elseif (!empty($product['ribbon'])) {
            $product['ribbon_class'] = 'product-ribbon';
        } elseif ($product['recommended']) {
            $product['ribbon']       = __('Recommended');
            $product['ribbon_class'] = 'product-ribbon';
        }
        if (!empty($product['ribbon']) && $product['price_percent'] > 0) {
            $product['ribbon'] = $product['ribbon'] . ' ' . _number($product['price_percent']) . '%';
        }

        // Set is new product
        $time = time() - ($config['new_product'] * 86400);
        if ($product['time_create'] > $time) {
            $product['is_new'] = 1;
        } else {
            $product['is_new'] = 0;
        }

        // return product
        return $product;
    }

    public function canonizeProductLight($product)
    {
        // Check
        if (empty($product)) {
            return '';
        }

        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // boject to array
        $product = $product->toArray();

        // Make setting
        $product['setting'] = json_decode($product['setting'], true);;

        // Set times
        $product['time_create_view'] = _date($product['time_create']);
        $product['time_update_view'] = _date($product['time_update']);

        // Set product url
        $product['productUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'product',
                    'slug'       => $product['slug'],
                ]
            )
        );

        // Set cart url
        $product['cartUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'checkout',
                    'action'     => 'add',
                    //'slug'          => $product['slug'],
                ]
            )
        );

        // Set category information
        $product['category'] = json_decode($product['category']);

        // Set discount
        $product = $this->canonizePriceAndDiscount($product, $config);

        // Set marketable
        $product['marketable'] = $this->marketable($product);

        // Set image
        if ($product['main_image']) {
            $product['thumbUrl']  = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('thumbnail')
            );
        } else {
            $product['thumbUrl']  = '';
        }

        // Set ribbon
        $product['ribbon_class'] = '';
        $saleId                  = Pi::api('sale', 'shop')->getInformation();
        if (in_array($product['id'], $saleId['product'])) {
            $product['ribbon']       = __('On sale');
            $product['ribbon_class'] = 'product-ribbon';
        } elseif (isset($product['price_discount']) && ($product['price_discount'] > $product['price'])) {
            $product['ribbon']       = __('Have discount');
            $product['ribbon_class'] = 'product-ribbon';
        } elseif (!empty($product['ribbon'])) {
            $product['ribbon_class'] = 'product-ribbon';
        } elseif ($product['recommended']) {
            $product['ribbon']       = __('Recommended');
            $product['ribbon_class'] = 'product-ribbon';
        }
        if (!empty($product['ribbon']) && $product['price_percent'] > 0) {
            $product['ribbon'] = $product['ribbon'] . ' ' . _number($product['price_percent']) . '%';
        }

        // Set is new product
        $time = time() - ($config['new_product'] * 86400);
        if ($product['time_create'] > $time) {
            $product['is_new'] = 1;
        } else {
            $product['is_new'] = 0;
        }

        // unset
        unset($product['text_summary']);
        unset($product['text_description']);
        unset($product['seo_title']);
        unset($product['seo_keywords']);
        unset($product['seo_description']);
        unset($product['comment']);
        unset($product['point']);
        unset($product['count']);
        unset($product['favorite']);
        unset($product['attach']);
        unset($product['attribute']);
        unset($product['related']);
        unset($product['stock_alert']);
        unset($product['price_discount']);
        unset($product['price_discount_view']);
        unset($product['uid']);
        unset($product['hits']);
        unset($product['sold']);

        // return product
        return $product;
    }

    public function canonizeProductOrder($product)
    {
        // Check
        if (empty($product)) {
            return '';
        }
        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // boject to array
        $product = $product->toArray();

        // Make setting
        $product['setting'] = json_decode($product['setting'], true);;

        // Set product url
        $product['productUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'product',
                    'slug'       => $product['slug'],
                ]
            )
        );

        // Set image
        if ($product['main_image']) {
            $product['thumbUrl']  = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('thumbnail')
            );
        } else {
            $product['thumbUrl']  = '';
        }

        // Set order product
        $productOrder = [
            'title'      => $product['title'],
            'productUrl' => $product['productUrl'],
            'thumbUrl'   => $product['thumbUrl'],
        ];

        // return product
        return $productOrder;
    }

    public function canonizeProductJson($product, $categoryList = [])
    {
        // Check
        if (empty($product)) {
            return '';
        }

        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Get category list
        $categoryList = (empty($categoryList)) ? Pi::registry('categoryList', 'shop')->read() : $categoryList;

        // boject to array
        $product = $product->toArray();

        // Make setting
        $product['setting'] = json_decode($product['setting'], true);

        // Set text
        $product['text_summary'] = Pi::service('markup')->render($product['text_summary'], 'html', 'html');
        $product['text_description'] = Pi::service('markup')->render($product['text_description'], 'html', 'html');
        $product['body'] = $product['text_summary'] . $product['text_description'];
        unset($product['text_summary']);
        unset($product['text_description']);

        // Set times
        $product['time_create_view'] = _date($product['time_create']);
        $product['time_update_view'] = _date($product['time_update']);

        // Set product url
        $product['productUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'product',
                    'slug'       => $product['slug'],
                ]
            )
        );

        // Set cart url
        $product['cartUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'checkout',
                    'action'     => 'add',
                    //'slug'          => $product['slug'],
                ]
            )
        );

        // Set cart url
        $product['cartJsonUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'json',
                    'action'     => 'add',
                    'id'         => $product['id'],
                ]
            )
        );

        // Set category information
        $product['category'] = json_decode($product['category']);

        // Set discount
        $product = $this->canonizePriceAndDiscount($product, $config);

        // Set stock
        switch ($product['stock_type']) {
            default:
            case 1:
                $product['stock_type_view'] = __('In stock');
                break;

            case 2:
                $product['stock_type_view'] = __('Out of stock');
                break;

            case 3:
                $product['stock_type_view'] = __('Coming soon');
                break;

            case 4:
                $product['stock_type_view'] = __('Contact');
                break;

            case 5:
                $product['stock_type_view'] = __('Variable stock');
                break;
        }

        // Set marketable
        $product['marketable'] = $this->marketable($product);

        // Set image
        if ($product['main_image']) {
            $product['largeUrl']  = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('large')
            );
            $product['mediumUrl'] = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('medium')
            );
            $product['thumbUrl']  = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('thumbnail')
            );
        } else {
            $product['largeUrl']  = '';
            $product['mediumUrl'] = '';
            $product['thumbUrl']  = '';
        }

        // Set category_main information
        $product['categoryMainTitle'] = $categoryList[$product['category_main']]['title'];

        // Set attribute
        if ($product['attribute'] && $config['view_attribute']) {
            $attributes = Pi::api('attribute', 'shop')->Product($product['id'], $product['category_main']);
            //$productSingle['attributes'] = $attributes['all'];
            foreach ($attributes['all'] as $attribute) {
                $product['attribute-' . $attribute['id']] = $attribute['data'];
            }
        }

        // Set is new product
        $time = time() - ($config['new_product'] * 86400);
        if ($product['time_create'] > $time) {
            $product['is_new'] = 1;
        } else {
            $product['is_new'] = 0;
        }

        // return product
        return $product;
    }

    public function canonizeProductFilter($product, $categoryList = [], $filterList = [])
    {
        // Check
        if (empty($product)) {
            return '';
        }

        // Get config
        $config = Pi::service('registry')->config->read($this->getModule());

        // Get category list
        $categoryList = (empty($categoryList)) ? Pi::registry('categoryList', 'shop')->read() : $categoryList;

        // boject to array
        $product = $product->toArray();

        // Make setting
        $product['setting'] = json_decode($product['setting'], true);

        // Set text
        $product['text_summary'] = Pi::service('markup')->render($product['text_summary'], 'html', 'html');
        $product['text_description'] = Pi::service('markup')->render($product['text_description'], 'html', 'html');

        // Set product url
        $product['productUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'product',
                    'slug'       => $product['slug'],
                ]
            )
        );

        // Set cart url
        $product['cartUrl'] = Pi::url(
            Pi::service('url')->assemble(
                'shop', [
                    'module'     => $this->getModule(),
                    'controller' => 'cart',
                    'action'     => 'add',
                    //'slug'          => $product['slug'],
                ]
            )
        );

        // Set category information
        $product['category'] = json_decode($product['category']);
        foreach ($product['category'] as $category) {
            $product['categories'][$category]['id']    = $categoryList[$category]['id'];
            $product['categories'][$category]['title'] = $categoryList[$category]['title'];
            $product['categories'][$category]['url']   = Pi::url(
                Pi::service('url')->assemble(
                    'shop', [
                        'module'     => $this->getModule(),
                        'controller' => 'category',
                        'slug'       => $categoryList[$category]['slug'],
                    ]
                )
            );
        }

        // Set discount
        $product = $this->canonizePriceAndDiscount($product, $config);

        // Set stock
        switch ($product['stock_type']) {
            default:
            case 1:
                $product['stock_type_view'] = __('In stock');
                break;

            case 2:
                $product['stock_type_view'] = __('Out of stock');
                break;

            case 3:
                $product['stock_type_view'] = __('Coming soon');
                break;

            case 4:
                $product['stock_type_view'] = __('Contact');
                break;

            case 5:
                $product['stock_type_view'] = __('Variable stock');
                break;
        }

        // Set marketable
        $product['marketable'] = $this->marketable($product);
        $product['marketable'] = ($product['marketable'] == 2) ? 1 : $product['marketable'];

        // Set image
        if ($product['main_image']) {
            $product['mediumUrl'] = Pi::url(
                (string)Pi::api('doc', 'media')->getSingleLinkUrl($product['main_image'])->setConfigModule('shop')->thumb('medium')
            );
        } else {
            $product['mediumUrl'] = '';
        }

        // Set ribbon
        $product['ribbon_class'] = '';
        $saleId                  = Pi::api('sale', 'shop')->getInformation();

        if (in_array($product['id'], $saleId['product'])) {
            $product['ribbon']       = __('On sale');
            $product['ribbon_class'] = 'product-ribbon';
        } elseif (isset($product['price_discount']) && ($product['price_discount'] > $product['price'])) {
            $product['ribbon']       = __('Have discount');
            $product['ribbon_class'] = 'product-ribbon';
        } elseif (!empty($product['ribbon'])) {
            $product['ribbon_class'] = 'product-ribbon';
        } elseif ($product['recommended']) {
            $product['ribbon']       = __('Recommended');
            $product['ribbon_class'] = 'product-ribbon';
        }
        if (!empty($product['ribbon']) && $product['price_percent'] > 0) {
            $product['ribbon'] = $product['ribbon'] . ' ' . _number($product['price_percent']) . '%';
        }

        // Set attribute
        $filterList = isset($filterList) ? $filterList : Pi::api('attribute', 'shop')->filterList();
        $attribute  = Pi::api('attribute', 'shop')->filterData($product['id'], $filterList);
        $product    = array_merge($product, $attribute);

        // Set is new product
        $time = time() - ($config['new_product'] * 86400);
        if ($product['time_create'] > $time) {
            $product['is_new'] = 1;
        } else {
            $product['is_new'] = 0;
        }

        // unset
        unset($product['text_summary']);
        unset($product['text_description']);
        unset($product['seo_title']);
        unset($product['seo_keywords']);
        unset($product['seo_description']);
        unset($product['comment']);
        unset($product['point']);
        unset($product['count']);
        unset($product['favorite']);
        unset($product['attach']);
        unset($product['attribute']);
        unset($product['related']);
        unset($product['recommended']);
        unset($product['stock']);
        unset($product['stock_alert']);
        unset($product['uid']);
        unset($product['setting']);

        // return product
        return $product;
    }

    public function sitemap()
    {
        if (Pi::service('module')->isActive('sitemap')) {
            // Remove old links
            Pi::api('sitemap', 'sitemap')->removeAll($this->getModule(), 'product');
            // find and import
            $columns = ['id', 'slug', 'status'];
            $select  = Pi::model('product', $this->getModule())->select()->columns($columns);
            $rowSet  = Pi::model('product', $this->getModule())->selectWith($select);
            foreach ($rowSet as $row) {
                // Make url
                $loc = Pi::url(
                    Pi::service('url')->assemble(
                        'shop', [
                            'module'     => $this->getModule(),
                            'controller' => 'product',
                            'slug'       => $row->slug,
                        ]
                    )
                );
                // Add to sitemap
                Pi::api('sitemap', 'sitemap')->groupLink($loc, $row->status, $this->getModule(), 'product', $row->id);
            }
        }
    }

    public function migrateMedia()
    {
        if (Pi::service("module")->isActive("media")) {

            $msg = '';

            // Get config
            $config = Pi::service('registry')->config->read($this->getModule());

            $productModel = Pi::model('product', $this->getModule());

            $select            = $productModel->select();
            $productCollection = $productModel->selectWith($select);

            foreach ($productCollection as $product) {

                $toSave = false;

                $mediaData = [
                    'active'       => 1,
                    'time_created' => time(),
                    'uid'          => $product->uid,
                    'count'        => 0,
                ];

                /**
                 * Check if media item have already migrate or no image to migrate
                 */
                if (!$product->main_image) {

                    /**
                     * Check if media item exists
                     */
                    if (empty($product['image']) || empty($product['path'])) {

                        $draft = $product->status == 3 ? ' (' . __('Draft') . ')' : '';

                        $msg .= __("Missing image or path value from db for product ID") . " " . $product->id . $draft . "<br>";
                    } else {
                        $imagePath = sprintf(
                            "upload/%s/original/%s/%s",
                            $config["image_path"],
                            $product["path"],
                            $product["image"]
                        );

                        $mediaData['title'] = $product->title;
                        $mediaId            = Pi::api('doc', 'media')->insertMedia($mediaData, $imagePath);

                        if ($mediaId) {
                            $product->main_image = $mediaId;
                            $toSave              = true;
                        }
                    }
                }

                if(!$product->additional_images){
                    $additionalImagesArray = array();

                    $attachList = Pi::api('product', 'shop')->attachList($product->id);

                    foreach($attachList as $type => $list){
                        foreach($list as $file){
                            if(empty($file["file"]) || empty($file["path"])){
                                $msg .= __("Missing file or path value from db for attachment ID") . " " .  $file->id . "<br>";
                            } else {
                                $attachPath = sprintf('upload/%s/original/%s/%s',
                                    $config['image_path'],
                                    $file['path'],
                                    $file['file']
                                );

                                $mediaData['title'] = $file['title'];
                                $mediaData['count'] = $file['hits'];

                                $mediaId = Pi::api('doc', 'media')->insertMedia($mediaData, $attachPath);

                                if($mediaId){
                                    $additionalImagesArray[] = $mediaId;
                                }
                            }
                        }
                    }

                    if($additionalImagesArray){
                        $product->additional_images = implode(',', $additionalImagesArray);
                        $toSave = true;
                    }
                }

                if ($toSave) {
                    $product->save();
                }
            }

            return $msg;
        }

        return false;
    }
}
