CREATE TABLE `{product}` (
  `id`               INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`            VARCHAR(255)        NOT NULL DEFAULT '',
  `subtitle`         VARCHAR(255)        NOT NULL DEFAULT '',
  `slug`             VARCHAR(255)        NOT NULL DEFAULT '',
  `category`         VARCHAR(255)        NOT NULL DEFAULT '',
  `category_main`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `text_summary`     TEXT,
  `text_description` TEXT,
  `seo_title`        VARCHAR(255)        NOT NULL DEFAULT '',
  `seo_keywords`     VARCHAR(255)        NOT NULL DEFAULT '',
  `seo_description`  VARCHAR(255)        NOT NULL DEFAULT '',
  `status`           TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `time_create`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_update`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `uid`              INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `hits`             INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `sold`             INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `image`            VARCHAR(255)        NOT NULL DEFAULT '',
  `path`             VARCHAR(16)         NOT NULL DEFAULT '',
  `comment`          INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `point`            INT(10)             NOT NULL DEFAULT '0',
  `count`            INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `favourite`        INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `attach`           TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `attribute`        TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `related`          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `recommended`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `stock`            INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `stock_alert`      INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `stock_type`       TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `price`            DECIMAL(16, 2)      NOT NULL DEFAULT '0.00',
  `price_discount`   DECIMAL(16, 2)      NOT NULL DEFAULT '0.00',
  `price_shipping`   DECIMAL(16, 2)      NOT NULL DEFAULT '0.00',
  `price_title`      VARCHAR(255)        NOT NULL DEFAULT '',
  `ribbon`           VARCHAR(64)         NOT NULL DEFAULT '',
  `setting`          TEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `title` (`title`),
  KEY `time_create` (`time_create`),
  KEY `status` (`status`),
  KEY `uid` (`uid`),
  KEY `recommended` (`recommended`),
  KEY `price` (`price`),
  KEY `stock` (`stock`),
  KEY `product_list` (`status`, `id`),
  KEY `product_order` (`time_create`, `id`),
  KEY `product_order_recommended` (`recommended`, `time_create`, `id`)
);

CREATE TABLE `{category}` (
  `id`               INT(10) UNSIGNED                NOT NULL AUTO_INCREMENT,
  `parent`           INT(5) UNSIGNED                 NOT NULL DEFAULT '0',
  `title`            VARCHAR(255)                    NOT NULL DEFAULT '',
  `slug`             VARCHAR(255)                    NOT NULL DEFAULT '',
  `image`            VARCHAR(255)                    NOT NULL DEFAULT '',
  `image_wide`       VARCHAR(255)                    NOT NULL DEFAULT '',
  `path`             VARCHAR(16)                     NOT NULL DEFAULT '',
  `text_summary`     TEXT,
  `text_description` TEXT,
  `seo_title`        VARCHAR(255)                    NOT NULL DEFAULT '',
  `seo_keywords`     VARCHAR(255)                    NOT NULL DEFAULT '',
  `seo_description`  VARCHAR(255)                    NOT NULL DEFAULT '',
  `time_create`      INT(10) UNSIGNED                NOT NULL DEFAULT '0',
  `time_update`      INT(10) UNSIGNED                NOT NULL DEFAULT '0',
  `setting`          TEXT,
  `status`           TINYINT(1) UNSIGNED             NOT NULL DEFAULT '1',
  `display_order`    INT(10) UNSIGNED                NOT NULL DEFAULT '0',
  `display_type`     ENUM ('product', 'subcategory') NOT NULL DEFAULT 'product',
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  KEY `parent` (`parent`),
  KEY `title` (`title`),
  KEY `time_create` (`time_create`),
  KEY `status` (`status`),
  KEY `display_order` (`display_order`),
  KEY `category_list` (`status`, `parent`, `display_order`, `id`)
);

CREATE TABLE `{link}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `product`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `category`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_update` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `recommended` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `price`       DECIMAL(16, 2)      NOT NULL DEFAULT '0.00',
  `stock`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product` (`product`),
  KEY `category` (`category`),
  KEY `time_create` (`time_create`),
  KEY `status` (`status`),
  KEY `price` (`price`),
  KEY `stock` (`stock`),
  KEY `category_list` (`status`, `category`, `time_create`),
  KEY `product_list` (`status`, `product`, `time_create`, `category`),
  KEY `link_order` (`time_create`, `id`),
  KEY `link_order_recommended` (`recommended`, `time_create`, `id`)
);

CREATE TABLE `{related}` (
  `id`              INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id`      INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `product_related` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `product_id` (`product_id`),
  KEY `product_related` (`product_related`),
  KEY `product_list` (`product_id`, `product_related`)
);

CREATE TABLE `{attach}` (
  `id`          INT(10) UNSIGNED                                                   NOT NULL AUTO_INCREMENT,
  `title`       VARCHAR(255)                                                       NOT NULL DEFAULT '',
  `file`        VARCHAR(255)                                                       NOT NULL DEFAULT '',
  `path`        VARCHAR(16)                                                        NOT NULL DEFAULT '',
  `product`     INT(10) UNSIGNED                                                   NOT NULL DEFAULT '0',
  `time_create` INT(10) UNSIGNED                                                   NOT NULL DEFAULT '0',
  `size`        INT(10) UNSIGNED                                                   NOT NULL DEFAULT '0',
  `type`        ENUM ('archive', 'image', 'video', 'audio', 'pdf', 'doc', 'other') NOT NULL DEFAULT 'image',
  `status`      TINYINT(1) UNSIGNED                                                NOT NULL DEFAULT '1',
  `hits`        INT(10) UNSIGNED                                                   NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `product` (`product`),
  KEY `time_create` (`time_create`),
  KEY `type` (`type`),
  KEY `product_status` (`product`, `status`)
);

CREATE TABLE `{field}` (
  `id`       INT(10) UNSIGNED                                                                                    NOT NULL AUTO_INCREMENT,
  `title`    VARCHAR(255)                                                                                        NOT NULL DEFAULT '',
  `icon`     VARCHAR(32)                                                                                         NOT NULL DEFAULT '',
  `type`     ENUM ('text', 'link', 'currency', 'date', 'number', 'select', 'video', 'audio', 'file', 'checkbox') NOT NULL DEFAULT 'text',
  `order`    INT(10) UNSIGNED                                                                                    NOT NULL DEFAULT '0',
  `status`   TINYINT(1) UNSIGNED                                                                                 NOT NULL DEFAULT '0' DEFAULT '1',
  `search`   TINYINT(1) UNSIGNED                                                                                 NOT NULL DEFAULT '0' DEFAULT '1',
  `position` INT(10) UNSIGNED                                                                                    NOT NULL DEFAULT '0',
  `value`    TEXT,
  `name`     VARCHAR(64)                                                                                                  DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `title` (`title`),
  KEY `order` (`order`),
  KEY `status` (`status`),
  KEY `search` (`search`),
  KEY `position` (`position`),
  KEY `order_status` (`order`, `status`)
);

CREATE TABLE `{field_category}` (
  `id`       INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `field`    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `category` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `field` (`field`),
  KEY `category` (`category`),
  KEY `field_category` (`field`, `category`)
);

CREATE TABLE `{field_position}` (
  `id`     INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`  VARCHAR(255)        NOT NULL DEFAULT '',
  `order`  INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status` TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `order` (`order`),
  KEY `status` (`status`),
  KEY `order_status` (`order`, `status`)
);

CREATE TABLE `{field_data}` (
  `id`      INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `field`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `product` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `data`    VARCHAR(255)     NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `field` (`field`),
  KEY `product` (`product`),
  KEY `data` (`data`),
  KEY `field_product` (`field`, `product`)
);

CREATE TABLE `{property}` (
  `id`              INT(10) UNSIGNED               NOT NULL AUTO_INCREMENT,
  `title`           VARCHAR(255)                   NOT NULL DEFAULT '',
  `order`           INT(10) UNSIGNED               NOT NULL DEFAULT '0',
  `status`          TINYINT(1) UNSIGNED            NOT NULL DEFAULT '1',
  `influence_stock` TINYINT(1) UNSIGNED            NOT NULL DEFAULT '1',
  `influence_price` TINYINT(1) UNSIGNED            NOT NULL DEFAULT '1',
  `type`            ENUM ('checkbox', 'selectbox') NOT NULL DEFAULT 'checkbox',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `order` (`order`),
  KEY `status` (`status`),
  KEY `order_status` (`order`, `status`)
);

CREATE TABLE `{property_value}` (
  `id`         INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `unique_key` VARCHAR(32)               DEFAULT NULL,
  `property`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `product`    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `name`       VARCHAR(255)     NOT NULL DEFAULT '',
  `stock`      INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `price`      DECIMAL(16, 2)   NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_key` (`unique_key`),
  KEY `property` (`property`),
  KEY `product` (`product`),
  KEY `name` (`name`),
  KEY `property_product` (`property`, `product`)
);

CREATE TABLE `{basket}` (
  `id`    INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid`   INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `value` VARCHAR(255)     NOT NULL DEFAULT '',
  `data`  TEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `value` (`value`)
);

CREATE TABLE `{discount}` (
  `id`       INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `title`    VARCHAR(255)        NOT NULL DEFAULT '',
  `role`     VARCHAR(64)         NOT NULL DEFAULT 'member',
  `percent`  TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `status`   TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `category` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `role` (`role`),
  KEY `category` (`category`),
  KEY `status` (`status`)
);

CREATE TABLE `{sale}` (
  `id`           INT(10) UNSIGNED             NOT NULL AUTO_INCREMENT,
  `type`         ENUM ('product', 'category') NOT NULL DEFAULT 'product',
  `product`      INT(10) UNSIGNED             NOT NULL DEFAULT '0',
  `category`     INT(10) UNSIGNED             NOT NULL DEFAULT '0',
  `percent`      TINYINT(3) UNSIGNED          NOT NULL DEFAULT '0',
  `price`        DECIMAL(16, 2)               NOT NULL DEFAULT '0.00',
  `time_publish` INT(10) UNSIGNED             NOT NULL DEFAULT '0',
  `time_expire`  INT(10) UNSIGNED             NOT NULL DEFAULT '0',
  `status`       TINYINT(1) UNSIGNED          NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `sale_select` (`status`, `type`, `time_publish`, `time_expire`),
  KEY `product` (`product`),
  KEY `category` (`category`),
  KEY `type` (`type`),
  KEY `time_publish` (`time_publish`),
  KEY `status` (`status`)
);

CREATE TABLE `{promotion}` (
  `id`              INT(10) UNSIGNED          NOT NULL AUTO_INCREMENT,
  `title`           VARCHAR(255)              NOT NULL DEFAULT '',
  `code`            VARCHAR(16)               NOT NULL DEFAULT '',
  `type`            ENUM ('percent', 'price') NOT NULL DEFAULT 'percent',
  `percent`         TINYINT(3) UNSIGNED       NOT NULL DEFAULT '0',
  `percent_partner` TINYINT(3) UNSIGNED       NOT NULL DEFAULT '0',
  `price`           DECIMAL(16, 2)            NOT NULL DEFAULT '0.00',
  `price_partner`   DECIMAL(16, 2)            NOT NULL DEFAULT '0.00',
  `time_publish`    INT(10) UNSIGNED          NOT NULL DEFAULT '0',
  `time_expire`     INT(10) UNSIGNED          NOT NULL DEFAULT '0',
  `status`          TINYINT(1) UNSIGNED       NOT NULL DEFAULT '1',
  `used`            INT(10) UNSIGNED          NOT NULL DEFAULT '0',
  `partner`         INT(10) UNSIGNED          NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `promotion_select` (`status`, `time_publish`, `time_expire`),
  KEY `partner` (`partner`),
  KEY `time_publish` (`time_publish`),
  KEY `status` (`status`)
);

CREATE TABLE `{question}` (
  `id`          INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `ip`          CHAR(15)            NOT NULL DEFAULT '',
  `product`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `name`        VARCHAR(255)        NOT NULL DEFAULT '',
  `email`       VARCHAR(255)        NOT NULL DEFAULT '',
  `status`      TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  `uid_ask`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `uid_answer`  INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `text_ask`    TEXT,
  `text_answer` TEXT,
  `time_ask`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_answer` INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `uid_ask` (`uid_ask`),
  KEY `uid_answer` (`uid_answer`),
  KEY `product` (`product`),
  KEY `status` (`status`)
);

CREATE TABLE `{log}` (
  `id`          INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uid`         INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `ip`          CHAR(15)         NOT NULL DEFAULT '',
  `time_create` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `section`     VARCHAR(32)      NOT NULL DEFAULT '',
  `item`        INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `operation`   VARCHAR(32)      NOT NULL DEFAULT '',
  `description` TEXT,
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `time_create` (`time_create`)
);

CREATE TABLE `{serial}` (
  `id`            INT(10) UNSIGNED    NOT NULL AUTO_INCREMENT,
  `product`       INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `status`        TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
  `serial_number` VARCHAR(255)        NOT NULL DEFAULT '',
  `time_create`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `time_expire`   INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `check_time`    INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `check_uid`     INT(10) UNSIGNED    NOT NULL DEFAULT '0',
  `check_ip`      CHAR(15)            NOT NULL DEFAULT '',
  `information`   TEXT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_number` (`serial_number`),
  KEY `product` (`product`),
  KEY `status` (`status`)
);