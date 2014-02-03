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
  `price_sign` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{category}` (
  `id` int (10) unsigned NOT NULL auto_increment,
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
  `id` int (10) unsigned NOT NULL auto_increment,
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
  `id` int (10) unsigned NOT NULL auto_increment,
  `product_id` int(10) unsigned NOT NULL,
  `product_related` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{attach}` (
  `id` int (10) unsigned NOT NULL auto_increment,
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
  `id` int (10) unsigned NOT NULL auto_increment,
  `title` varchar (255) NOT NULL,
  `image` varchar (255) NOT NULL,
  `type` enum('text','link','currency','date','number','select','video','audio','file') NOT NULL,
  `order` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL default '1',
  `search` tinyint(1) unsigned NOT NULL default '1',
  `value` text,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{field_data}` (
  `id` int (10) unsigned NOT NULL auto_increment,
  `field` int(10) unsigned NOT NULL,
  `product` int(10) unsigned NOT NULL,
  `data` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{special}` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `product` int(10) unsigned NOT NULL,
  `price` decimal(16,2) NOT NULL,
  `time_publish` int(10) unsigned NOT NULL,
  `time_expire` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY (`id`)
);

CREATE TABLE `{review}` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL,
  `product` int(10) unsigned NOT NULL,
  `title` varchar (255) NOT NULL,
  `description` text,
  `time_create` int(10) unsigned NOT NULL,
  `official` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{order}` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL,
  `code` varchar (16) NOT NULL,
  `first_name` varchar (255) NOT NULL,
  `last_name` varchar (255) NOT NULL,
  `email` varchar (64) NOT NULL,
  `phone` varchar (16) NOT NULL,
  `mobile` varchar (16) NOT NULL,
  `company` varchar (255) NOT NULL,
  `address` text,
  `country` varchar (64) NOT NULL,
  `city` varchar (64) NOT NULL,
  `zip_code` varchar (16) NOT NULL,
  `ip` char(15) NOT NULL,
  `status_order` tinyint(1) unsigned NOT NULL,
  `status_payment` tinyint(1) unsigned NOT NULL,
  `status_delivery` tinyint(1) unsigned NOT NULL,
  `time_create` int(10) unsigned NOT NULL,
  `time_payment` int(10) unsigned NOT NULL,
  `time_delivery` int(10) unsigned NOT NULL,
  `time_finish` int(10) unsigned NOT NULL,
  `user_note` text,
  `admin_note` text,
  `number` int(10) unsigned NOT NULL,
  `product_price` double(16,2) NOT NULL,
  `discount_price` double(16,2) NOT NULL,
  `shipping_price` decimal(16,2) NOT NULL,
  `packing_price` decimal(16,2) NOT NULL,
  `total_price` double(16,2) NOT NULL,
  `paid_price` double(16,2) NOT NULL,
  `packing` tinyint(1) unsigned NOT NULL,
  `delivery` int(10) unsigned NOT NULL,
  `location` int(10) unsigned NOT NULL,
  `payment_method` enum('online','offline') NOT NULL,
  `payment_adapter` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{order_basket}` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `order` int(10) unsigned NOT NULL,
  `product` int(10) unsigned NOT NULL,
  `product_price` double(16,2) NOT NULL,
  `discount_price` double(16,2) NOT NULL,
  `total_price` double(16,2) NOT NULL,
  `number` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{user}` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `uid` int(10) unsigned NOT NULL,
  `first_name` varchar (255) NOT NULL,
  `last_name` varchar (255) NOT NULL,
  `email` varchar (64) NOT NULL,
  `phone` varchar (16) NOT NULL,
  `mobile` varchar (16) NOT NULL,
  `company` varchar (255) NOT NULL,
  `address` text,
  `country` varchar (64) NOT NULL,
  `city` varchar (64) NOT NULL,
  `zip_code` varchar (16) NOT NULL,
  `admin_note` text,
  `user_note` text,
  `number` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{delivery}` (
  `id` int (10) unsigned NOT NULL auto_increment,
  `title` varchar (255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{delivery_payment}` (
  `id` int (10) unsigned NOT NULL auto_increment,
  `delivery` int(5) unsigned NOT NULL,
  `payment` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{location}` (
  `id` int (10) unsigned NOT NULL auto_increment,
  `parent` int(5) unsigned NOT NULL default '0',
  `title` varchar (255) NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);

CREATE TABLE `{location_delivery}` (
  `id` int (10) unsigned NOT NULL auto_increment,
  `location` int(5) unsigned NOT NULL,
  `delivery` int(5) unsigned NOT NULL,
  `price` decimal(16,2) NOT NULL,
  `delivery_time` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`)
);