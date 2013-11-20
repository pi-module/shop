CREATE TABLE `{product}` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `summary` text,
  `description` text,
  `seo_title` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `time_create` int(10) unsigned NOT NULL,
  `time_update` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  `sales` int(10) unsigned NOT NULL,
  `image` varchar(255) NOT NULL,
  `path` varchar(16) NOT NULL,
  `comment` int(10) unsigned NOT NULL,
  `point` int(10) NOT NULL,
  `count` int(10) unsigned NOT NULL,
  `favorite` int(10) unsigned NOT NULL,
  `attach` tinyint(3) unsigned NOT NULL,
  `extra` tinyint(3) unsigned NOT NULL,
  `related` tinyint(3) unsigned NOT NULL,
  `review` tinyint(3) unsigned NOT NULL,
  `recommended` tinyint(1) unsigned NOT NULL,
  `stock` int(10) unsigned NOT NULL,
  `stock_alert` int(10) unsigned NOT NULL,
  `price` decimal(16,2) NOT NULL,
  `price_discount` decimal(16,2) NOT NULL,
  `property_1` varchar(255) NOT NULL,
  `property_2` varchar(255) NOT NULL,
  `property_3` varchar(255) NOT NULL,
  `property_4` varchar(255) NOT NULL,
  `property_5` varchar(255) NOT NULL,
  `property_6` varchar(255) NOT NULL,
  `property_7` varchar(255) NOT NULL,
  `property_8` varchar(255) NOT NULL,
  `property_9` varchar(255) NOT NULL,
  `property_10` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{category}` (
  `id` int (10) unsigned NOT NULL  auto_increment,
  `parent` int(5) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL,
  `path` varchar(16) NOT NULL,
  `description` text,
  `description_footer` text,
  `seo_title` varchar(255) NOT NULL,
  `seo_keywords` varchar(255) NOT NULL,
  `seo_description` varchar(255) NOT NULL,
  `time_create` int(10) unsigned NOT NULL,
  `time_update` int(10) unsigned NOT NULL,
  `setting` text,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{link}` (
  `id` int (10) unsigned NOT NULL  auto_increment,
  `product` int(10) unsigned NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `time_create` int(10) unsigned NOT NULL,
  `time_update` int(10) unsigned NOT NULL,
  `price` decimal(16,2) NOT NULL,
  `stock` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{related}` (
  `id` int (10) unsigned NOT NULL  auto_increment,
  `product_id` int(10) unsigned NOT NULL,
  `product_related` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{attach}` (
  `id` int (10) unsigned NOT NULL  auto_increment,
  `title` varchar (255) NOT NULL,
  `file` varchar (255) NOT NULL,
  `path` varchar(16) NOT NULL,
  `product` int(10) unsigned NOT NULL,
  `time_create` int(10) unsigned NOT NULL,
  `size` int(10) unsigned NOT NULL,
  `type` enum('archive','image','video','audio','pdf','doc','other') NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `hits` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{field}` (
  `id` int (10) unsigned NOT NULL  auto_increment,
  `title` varchar (255) NOT NULL,
  `image` varchar (255) NOT NULL,
  `type` enum('text','link','currency','date','number') NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL default '1',
  `search` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE `{field_data}` (
  `id` int (10) unsigned NOT NULL  auto_increment,
  `field` int(10) unsigned NOT NULL,
  `product` int(10) unsigned NOT NULL,
  `data` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{spotlight}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  `product` int(10) unsigned NOT NULL,
  `category` int(10) unsigned NOT NULL,
  `time_publish` int(10) unsigned NOT NULL,
  `time_expire` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE `{review}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  `user` int(10) unsigned NOT NULL,
  `product` int(10) unsigned NOT NULL,
  `title` varchar (255) NOT NULL,
  `description` text,
  `time_create` int(10) unsigned NOT NULL,
  `official` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{order}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{order_basket}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{packing}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{location}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{delivery}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{payment}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{location_delivery}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{delivery_payment}` (
  `id` int(10) unsigned NOT NULL  auto_increment,
  PRIMARY KEY (`id`)
);