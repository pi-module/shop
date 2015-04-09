CREATE TABLE `{product}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL default '',
    `slug` varchar(255) NOT NULL default '',
    `category` varchar(255) NOT NULL default '',
    `category_main` int(10) unsigned NOT NULL default '0',
    `text_summary` text,
    `text_description` text,
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
    `attribute` tinyint(3) unsigned NOT NULL default '0',
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
    `text_description` text,
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
    `icon` varchar(32) NOT NULL default '',
    `type` enum('text','link','currency','date','number','select','video','audio','file', 'checkbox') NOT NULL default 'text',
    `order` int(10) unsigned NOT NULL default '0',
    `status` tinyint(1) unsigned NOT NULL default '1',
    `search` tinyint(1) unsigned NOT NULL default '1',
    `position` int(10) unsigned NOT NULL default '0',
    `value` text,
    PRIMARY KEY (`id`),
    KEY `title` (`title`),
    KEY `order` (`order`),
    KEY `status` (`status`),
    KEY `search` (`search`),
    KEY `position` (`position`),
    KEY `order_status` (`order`, `status`)
);

CREATE TABLE `{field_category}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `field` int(10) unsigned NOT NULL default '0',
    `category` int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (`id`),
    KEY `field` (`field`),
    KEY `category` (`category`),
    KEY `field_category` (`field`, `category`)
);

CREATE TABLE `{field_position}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `title` varchar(255) NOT NULL default '',
    `order` int(10) unsigned NOT NULL default '0',
    `status` tinyint(1) unsigned NOT NULL default '1',
    PRIMARY KEY (`id`),
    KEY `title` (`title`),
    KEY `order` (`order`),
    KEY `status` (`status`),
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