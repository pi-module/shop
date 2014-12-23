CREATE TABLE `{product}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL default '',
    `slug` varchar(255) NOT NULL default '',
    `category` varchar(255) NOT NULL default '',
    `brand` int(10) unsigned NOT NULL default '0',
    `summary` text,
    `description` text,
    `seo_title` varchar(255) NOT NULL default '',
    `seo_keywords` varchar(255) NOT NULL default '',
    `seo_description` varchar(255) NOT NULL default '',
    `status` tinyint(1) unsigned NOT NULL default '1',
    `time_create` int(10) unsigned NOT NULL default '0',
    `time_update` int(10) unsigned NOT NULL default '0',
    `uid` int(10) unsigned NOT NULL default '0',
    `hits` int(10) unsigned NOT NULL default '0',
    `sales` int(10) unsigned NOT NULL default '0',
    `image` varchar(255) NOT NULL default '',
    `path` varchar(16) NOT NULL default '',
    `comment` int(10) unsigned NOT NULL default '0',
    `point` int(10) NOT NULL default '0',
    `count` int(10) unsigned NOT NULL default '0',
    `favourite` int(10) unsigned NOT NULL default '0',
    `attach` tinyint(3) unsigned NOT NULL default '0',
    `extra` tinyint(3) unsigned NOT NULL default '0',
    `related` tinyint(3) unsigned NOT NULL default '0',
    `recommended` tinyint(1) unsigned NOT NULL default '0',
    `stock` int(10) unsigned NOT NULL default '0',
    `stock_alert` int(10) unsigned NOT NULL default '0',
    `stock_type` tinyint(1) unsigned NOT NULL default '1',
    `price` decimal(16,2) NOT NULL default '0.00',
    `price_discount` decimal(16,2) NOT NULL default '0.00',
    `price_title` varchar(255) NOT NULL default '',
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `title` (`title`),
    KEY `time_create` (`time_create`),
    KEY `status` (`status`),
    KEY `uid` (`uid`),
    KEY `product_list` (`status`, `id`),
    KEY `product_order` (`time_create`, `id`)
);

CREATE TABLE `{category}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `parent` int(5) unsigned NOT NULL default '0',
    `title` varchar(255) NOT NULL default '',
    `slug` varchar(255) NOT NULL default '',
    `image` varchar(255) NOT NULL default '',
    `path` varchar(16) NOT NULL default '',
    `description` text,
    `seo_title` varchar(255) NOT NULL default '',
    `seo_keywords` varchar(255) NOT NULL default '',
    `seo_description` varchar(255) NOT NULL default '',
    `time_create` int(10) unsigned NOT NULL default '0',
    `time_update` int(10) unsigned NOT NULL default '0',
    `setting` text,
    `status` tinyint(1) unsigned NOT NULL default '1',
    PRIMARY KEY (`id`),
    UNIQUE KEY `slug` (`slug`),
    KEY `parent` (`parent`),
    KEY `title` (`title`),
    KEY `time_create` (`time_create`),
    KEY `status` (`status`),
    KEY `category_list` (`status`, `parent`, `id`)
);

CREATE TABLE `{link}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `product` int(10) unsigned NOT NULL default '0',
    `category` int(10) unsigned NOT NULL default '0',
    `time_create` int(10) unsigned NOT NULL default '0',
    `time_update` int(10) unsigned NOT NULL default '0',
    `price` decimal(16,2) NOT NULL default '0.00',
    `stock` int(10) unsigned NOT NULL default '0',
    `status` tinyint(1) unsigned NOT NULL default '1',
    PRIMARY KEY (`id`),
    KEY `product` (`product`),
    KEY `category` (`category`),
    KEY `time_create` (`time_create`),
    KEY `status` (`status`),
    KEY `price` (`price`),
    KEY `stock` (`stock`),
    KEY `category_list` (`status`, `category`, `time_create`),
    KEY `product_list` (`status`, `product`, `time_create`, `category`),
    KEY `link_order` (`time_create`, `id`)
);

CREATE TABLE `{related}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `product_id` int(10) unsigned NOT NULL default '0',
    `product_related` int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (`id`),
    KEY `product_id` (`product_id`),
    KEY `product_related` (`product_related`),
    KEY `product_list` (`product_id`, `product_related`)
);

CREATE TABLE `{attach}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL default '',
    `file` varchar(255) NOT NULL default '',
    `path` varchar(16) NOT NULL default '',
    `product` int(10) unsigned NOT NULL default '0',
    `time_create` int(10) unsigned NOT NULL default '0',
    `size` int(10) unsigned NOT NULL default '0',
    `type` enum('archive','image','video','audio','pdf','doc','other') NOT NULL default 'image',
    `status` tinyint(1) unsigned NOT NULL default '1',
    `hits` int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (`id`),
    KEY `title` (`title`),
    KEY `product` (`product`),
    KEY `time_create` (`time_create`),
    KEY `type` (`type`),
    KEY `product_status` (`product`, `status`)
);

CREATE TABLE `{field}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL default '',
    `image` varchar(255) NOT NULL default '',
    `type` enum('text','link','currency','date','number','select','video','audio','file') NOT NULL default 'text',
    `order` int(10) unsigned NOT NULL default '0',
    `status` tinyint(1) unsigned NOT NULL default '1',
    `search` tinyint(1) unsigned NOT NULL default '1',
    `value` text,
    PRIMARY KEY (`id`),
    KEY `title` (`title`),
    KEY `order` (`order`),
    KEY `status` (`status`),
    KEY `search` (`search`),
    KEY `order_status` (`order`, `status`)
);

CREATE TABLE `{field_data}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `field` int(10) unsigned NOT NULL default '0',
    `product` int(10) unsigned NOT NULL default '0',
    `data` varchar(255) NOT NULL default '',
    PRIMARY KEY (`id`),
    KEY `field` (`field`),
    KEY `product` (`product`),
    KEY `data` (`data`),
    KEY `field_product` (`field`, `product`)
);

CREATE TABLE `{special}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `product` int(10) unsigned NOT NULL default '0',
    `price` decimal(16,2) NOT NULL default '0.00',
    `time_publish` int(10) unsigned NOT NULL default '0',
    `time_expire` int(10) unsigned NOT NULL default '0',
    `status` tinyint(1) unsigned NOT NULL default '1',
    PRIMARY KEY (`id`),
    KEY `special_select` (`status`, `time_publish`, `time_expire`),
    KEY `product` (`product`),
    KEY `time_publish` (`time_publish`),
    KEY `status` (`status`)
);

CREATE TABLE `{order}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `uid` int(10) unsigned NOT NULL default '0',
    `code` varchar(16) NOT NULL default '',
    `first_name` varchar(255) NOT NULL default '',
    `last_name` varchar(255) NOT NULL default '',
    `email` varchar(64) NOT NULL default '',
    `phone` varchar(16) NOT NULL default '',
    `mobile` varchar(16) NOT NULL default '',
    `company` varchar(255) NOT NULL default '',
    `address` text,
    `country` varchar(64) NOT NULL default '',
    `city` varchar(64) NOT NULL default '',
    `zip_code` varchar(16) NOT NULL default '',
    `ip` char(15) NOT NULL default '',
    `status_order` tinyint(1) unsigned NOT NULL default '0',
    `status_payment` tinyint(1) unsigned NOT NULL default '0',
    `status_delivery` tinyint(1) unsigned NOT NULL default '0',
    `time_create` int(10) unsigned NOT NULL default '0',
    `time_payment` int(10) unsigned NOT NULL default '0',
    `time_delivery` int(10) unsigned NOT NULL default '0',
    `time_finish` int(10) unsigned NOT NULL default '0',
    `user_note` text,
    `admin_note` text,
    `number` int(10) unsigned NOT NULL default '0',
    `product_price` decimal(16,2) NOT NULL default '0.00',
    `discount_price` decimal(16,2) NOT NULL default '0.00',
    `shipping_price` decimal(16,2) NOT NULL default '0.00',
    `packing_price` decimal(16,2) NOT NULL default '0.00',
    `total_price` decimal(16,2) NOT NULL default '0.00',
    `paid_price` decimal(16,2) NOT NULL default '0.00',
    `packing` tinyint(1) unsigned NOT NULL default '0',
    `delivery` int(10) unsigned NOT NULL default '0',
    `location` int(10) unsigned NOT NULL default '0',
    `payment_method` enum('online','offline') NOT NULL default 'online',
    `payment_adapter` varchar(64) NOT NULL default '',
    PRIMARY KEY (`id`),
    KEY `uid` (`uid`),
    KEY `code` (`code`),
    KEY `status_order` (`status_order`),
    KEY `status_payment` (`status_payment`),
    KEY `status_delivery` (`status_delivery`),
    KEY `time_create` (`time_create`)
);

CREATE TABLE `{order_basket}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `order` int(10) unsigned NOT NULL default '0',
    `product` int(10) unsigned NOT NULL default '0',
    `product_price` decimal(16,2) NOT NULL default '0.00',
    `discount_price` decimal(16,2) NOT NULL default '0.00',
    `total_price` decimal(16,2) NOT NULL default '0.00',
    `number` int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (`id`),
    KEY `order` (`order`),
    KEY `product` (`product`)
);

CREATE TABLE `{user}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `uid` int(10) unsigned NOT NULL default '0',
    `first_name` varchar(255) NOT NULL default '',
    `last_name` varchar(255) NOT NULL default '',
    `email` varchar(64) NOT NULL default '',
    `phone` varchar(16) NOT NULL default '',
    `mobile` varchar(16) NOT NULL default '',
    `company` varchar(255) NOT NULL default '',
    `address` text,
    `country` varchar(64) NOT NULL default '',
    `city` varchar(64) NOT NULL default '',
    `zip_code` varchar(16) NOT NULL default '',
    `admin_note` text,
    `user_note` text,
    `number` int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (`id`),
    KEY `uid` (`uid`)
);

CREATE TABLE `{delivery}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL default '',
    `status` tinyint(1) unsigned NOT NULL default '1',
    PRIMARY KEY (`id`),
    KEY `title` (`title`),
    KEY `status` (`status`)
);

CREATE TABLE `{delivery_payment}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `delivery` int(5) unsigned NOT NULL default '0',
    `payment` varchar(64) NOT NULL default '',
    PRIMARY KEY (`id`),
    KEY `delivery` (`delivery`),
    KEY `payment` (`payment`),
    KEY `delivery_payment` (`delivery`, `payment`)
);

CREATE TABLE `{location}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `parent` int(5) unsigned NOT NULL default '0',
    `title` varchar(255) NOT NULL default '',
    `status` tinyint(1) unsigned NOT NULL default '1',
    PRIMARY KEY (`id`),
    KEY `parent` (`parent`),
    KEY `title` (`title`),
    KEY `status` (`status`)
);

CREATE TABLE `{location_delivery}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `location` int(5) unsigned NOT NULL default '0',
    `delivery` int(5) unsigned NOT NULL default '0',
    `price` decimal(16,2) NOT NULL default '0.00',
    `delivery_time` mediumint(8) unsigned NOT NULL default '0',
    PRIMARY KEY (`id`),
    KEY `location` (`location`),
    KEY `delivery` (`delivery`),
    KEY `location_delivery` (`location`, `delivery`)
);

CREATE TABLE `{log}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `uid` int(10) unsigned NOT NULL default '0',
    `ip` char(15) NOT NULL default '',
    `time_create` int(10) unsigned NOT NULL default '0',
    `section` varchar (32) NOT NULL default '',
    `item` int(10) unsigned NOT NULL default '0',
    `operation` varchar (32) NOT NULL default '',
    `description` text,
    PRIMARY KEY (`id`),
    KEY `uid` (`uid`),
    KEY `time_create` (`time_create`)
);