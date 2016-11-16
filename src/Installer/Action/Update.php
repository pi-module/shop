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
namespace Module\Shop\Installer\Action;

use Pi;
use Pi\Application\Installer\Action\Update as BasicUpdate;
use Pi\Application\Installer\SqlSchema;
use Zend\EventManager\Event;
use Zend\Json\Json;

class Update extends BasicUpdate
{
    /**
     * {@inheritDoc}
     */
    protected function attachDefaultListeners()
    {
        $events = $this->events;
        $events->attach('update.pre', array($this, 'updateSchema'));
        parent::attachDefaultListeners();

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function updateSchema(Event $e)
    {
        $moduleVersion = $e->getParam('version');

        // Set product model
        $productModel = Pi::model('product', $this->module);
        $productTable = $productModel->getTable();
        $productAdapter = $productModel->getAdapter();

        // Set category model
        $categoryModel = Pi::model('category', $this->module);
        $categoryTable = $categoryModel->getTable();
        $categoryAdapter = $categoryModel->getAdapter();

        // Set field model
        $fieldModel = Pi::model('field', $this->module);
        $fieldTable = $fieldModel->getTable();
        $fieldAdapter = $fieldModel->getAdapter();

        // Set property model
        $propertyValueModel = Pi::model('property_value', $this->module);
        $propertyValueTable = $propertyValueModel->getTable();
        $propertyValueAdapter = $propertyValueModel->getAdapter();

        // Set property model
        $discountModel = Pi::model('discount', $this->module);
        $discountTable = $discountModel->getTable();
        $discountAdapter = $discountModel->getAdapter();

        // Set sale model
        $saleModel = Pi::model('sale', $this->module);
        $saleTable = $saleModel->getTable();
        $saleAdapter = $saleModel->getAdapter();

        // Set promotion model
        $promotionModel = Pi::model('promotion', $this->module);
        $promotionTable = $promotionModel->getTable();
        $promotionAdapter = $promotionModel->getAdapter();

        // Update to version 0.3.0
        if (version_compare($moduleVersion, '0.3.0', '<')) {
            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s ADD `brand` int(10) unsigned NOT NULL default '0'", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 0.3.3
        if (version_compare($moduleVersion, '0.3.3', '<')) {
            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s ADD `stock_type` tinyint(1) unsigned NOT NULL default '1'", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 0.3.7
        if (version_compare($moduleVersion, '0.3.7', '<')) {
            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s CHANGE `extra` `attribute` tinyint(3) unsigned NOT NULL default '0'", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 0.3.8
        if (version_compare($moduleVersion, '0.3.8', '<')) {

            // Alter table field `summary`
            $sql = sprintf("ALTER TABLE %s CHANGE `summary` `text_summary` text", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table field `description`
            $sql = sprintf("ALTER TABLE %s CHANGE `description` `text_description` text", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table field `description`
            $sql = sprintf("ALTER TABLE %s CHANGE `description` `text_description` text", $categoryTable);
            try {
                $categoryAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.0.5
        if (version_compare($moduleVersion, '1.0.5', '<')) {

            // Add table of field_category
            $sql = <<<'EOD'
CREATE TABLE `{field_category}` (
    `id` int (10) unsigned NOT NULL auto_increment,
    `field` int(10) unsigned NOT NULL default '0',
    `category` int(10) unsigned NOT NULL default '0',
    PRIMARY KEY (`id`),
    KEY `field` (`field`),
    KEY `category` (`category`),
    KEY `field_category` (`field`, `category`)
);
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }

            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s CHANGE `brand` `category_main` int(10) unsigned NOT NULL default '0'", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table field `icon`
            $sql = sprintf("ALTER TABLE %s ADD `icon` varchar(32) NOT NULL default ''", $fieldTable);
            try {
                $fieldAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table field `position`
            $sql = sprintf("ALTER TABLE %s ADD `position` int(10) unsigned NOT NULL default '0' , ADD INDEX (`position`)", $fieldTable);
            try {
                $fieldAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s CHANGE `type` `type` enum('text','link','currency','date','number','select','video','audio','file', 'checkbox') NOT NULL default 'text'", $fieldTable);
            try {
                $fieldAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.0.8
        if (version_compare($moduleVersion, '1.0.8', '<')) {
            // Add table of field_position
            $sql = <<<'EOD'
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
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }
            // Alter table : DROP image
            $sql = sprintf("ALTER TABLE %s DROP `image`;", $fieldTable);
            try {
                $fieldAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.0.9
        if (version_compare($moduleVersion, '1.0.9', '<')) {
            // Add table of basket
            $sql = <<<'EOD'
CREATE TABLE `{basket}` (
    `id` int(10) unsigned NOT NULL auto_increment,
    `uid` int(10) unsigned NOT NULL default '0',
    `value` varchar(255) NOT NULL default '',
    `data` text,
    PRIMARY KEY (`id`),
    UNIQUE KEY `value` (`value`)
);
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }

            // Alter table field `setting`
            $sql = sprintf("ALTER TABLE %s ADD `setting` text", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.1.0
        if (version_compare($moduleVersion, '1.1.0', '<')) {
            // Update value
            $select = $fieldModel->select();
            $rowset = $fieldModel->selectWith($select);
            foreach ($rowset as $row) {
                // Set value
                $value = array(
                    'data' => $row->value,
                    'default' => '',
                );
                $value = Json::encode($value);
                // Save value
                $row->value = $value;
                $row->save();
            }
            // Alter table : ADD name
            $sql = sprintf("ALTER TABLE %s ADD `name` varchar(64) default NULL , ADD UNIQUE `name` (`name`)", $fieldTable);
            try {
                $fieldAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.1.1
        if (version_compare($moduleVersion, '1.1.1', '<')) {
            // Add table of property
            $sql = <<<'EOD'
CREATE TABLE `{property}` (
  `id`              INT(10) UNSIGNED              NOT NULL AUTO_INCREMENT,
  `title`           VARCHAR(255)                  NOT NULL DEFAULT '',
  `order`           INT(10) UNSIGNED              NOT NULL DEFAULT '0',
  `status`          TINYINT(1) UNSIGNED           NOT NULL DEFAULT '1',
  `influence_stock` TINYINT(1) UNSIGNED           NOT NULL DEFAULT '1',
  `influence_price` TINYINT(1) UNSIGNED           NOT NULL DEFAULT '1',
  `type`            ENUM('checkbox', 'selectbox') NOT NULL DEFAULT 'checkbox',
  PRIMARY KEY (`id`),
  KEY `title` (`title`),
  KEY `order` (`order`),
  KEY `status` (`status`),
  KEY `order_status` (`order`, `status`)
);
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }

            // Add table of property_value
            $sql = <<<'EOD'
CREATE TABLE `{property_value}` (
  `id`       INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `property` INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `product`  INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `name`     VARCHAR(255)     NOT NULL DEFAULT '',
  `stock`    INT(10) UNSIGNED NOT NULL DEFAULT '0',
  `price`    DECIMAL(16, 2)   NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  KEY `property` (`property`),
  KEY `product` (`product`),
  KEY `name` (`name`),
  KEY `property_product` (`property`, `product`)
);
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }
        }

        // Update to version 1.1.6
        if (version_compare($moduleVersion, '1.1.6', '<')) {
            // Alter table : ADD display_order
            $sql = sprintf("ALTER TABLE %s ADD `display_order` int(10) unsigned NOT NULL default '0' , ADD INDEX (`display_order`) ", $categoryTable);
            try {
                $categoryAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.1.8
        if (version_compare($moduleVersion, '1.1.8', '<')) {
            // Add table of discount
            $sql = <<<'EOD'
CREATE TABLE `{discount}` (
  `id`      INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title`   VARCHAR(255)        NOT NULL DEFAULT '',
  `role`    VARCHAR(64)      NOT NULL DEFAULT 'member',
  `percent` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
  `status`  TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role` (`role`),
  KEY `status` (`status`)
);
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }
        }

        // Update to version 1.2.4
        if (version_compare($moduleVersion, '1.2.4', '<')) {
            // Alter table : ADD unique_key
            $sql = sprintf("ALTER TABLE %s ADD `unique_key` varchar(32) DEFAULT NULL , ADD UNIQUE `unique_key` (`unique_key`)", $propertyValueTable);
            try {
                $propertyValueAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
            // Update value
            $select = $propertyValueModel->select();
            $rowset = $propertyValueModel->selectWith($select);
            foreach ($rowset as $row) {
                $key = md5($row->id);
                // Save value
                $row->unique_key = $key;
                $row->save();
            }
        }

        // Update to version 1.3.5
        if (version_compare($moduleVersion, '1.3.5', '<')) {
            // Alter table : ADD subtitle
            $sql = sprintf("ALTER TABLE %s ADD `subtitle` VARCHAR(255) NOT NULL DEFAULT ''", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.3.7
        if (version_compare($moduleVersion, '1.3.7', '<')) {
            // Add table of discount
            $sql = <<<'EOD'
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
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }
        }

        // Update to version 1.3.8
        if (version_compare($moduleVersion, '1.3.8', '<')) {

            // Alter table : Update index
            $sql = sprintf("ALTER TABLE %s DROP INDEX `role`, ADD INDEX `role` (`role`) USING BTREE", $discountTable);
            try {
                $discountAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table : ADD category
            $sql = sprintf("ALTER TABLE %s ADD `category` INT(10) UNSIGNED NOT NULL DEFAULT '0', ADD INDEX (`category`)", $discountTable);
            try {
                $discountAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        if (version_compare($moduleVersion, '1.3.9', '<')) {
            // Alter table : ADD price_shipping
            $sql = sprintf("ALTER TABLE %s ADD `price_shipping` DECIMAL(16, 2) NOT NULL DEFAULT '0.00'", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        if (version_compare($moduleVersion, '1.4.0', '<')) {
            // Alter table : ADD price_shipping
            $sql = sprintf("ALTER TABLE %s ADD `ribbon` VARCHAR(64) NOT NULL DEFAULT ''", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        if (version_compare($moduleVersion, '1.4.1', '<')) {
            // Alter table : Update sold
            $sql = sprintf("ALTER TABLE %s CHANGE `sales` `sold` INT(10) UNSIGNED NOT NULL DEFAULT '0'", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Rename special table
            $sql = "RENAME TABLE {special} TO {sale}";
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }
        }

        if (version_compare($moduleVersion, '1.4.2', '<')) {
            // Add table of discount
            $sql = <<<'EOD'
CREATE TABLE `{promotion}` (
  `id`           INT(10) UNSIGNED          NOT NULL AUTO_INCREMENT,
  `title`        VARCHAR(255)              NOT NULL DEFAULT '',
  `code`         VARCHAR(16)               NOT NULL DEFAULT '',
  `type`         ENUM ('percent', 'price') NOT NULL DEFAULT 'percent',
  `percent`      TINYINT(3) UNSIGNED       NOT NULL DEFAULT '0',
  `price`        DECIMAL(16, 2)            NOT NULL DEFAULT '0.00',
  `time_publish` INT(10) UNSIGNED          NOT NULL DEFAULT '0',
  `time_expire`  INT(10) UNSIGNED          NOT NULL DEFAULT '0',
  `status`       TINYINT(1) UNSIGNED       NOT NULL DEFAULT '1',
  `used`         INT(10) UNSIGNED          NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `promotion_select` (`status`, `time_publish`, `time_expire`),
  KEY `time_publish` (`time_publish`),
  KEY `status` (`status`)
);
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }
        }

        if (version_compare($moduleVersion, '1.4.6', '<')) {
            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s ADD `text_summary` TEXT", $categoryTable);
            try {
                $categoryAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        if (version_compare($moduleVersion, '1.4.7', '<')) {
            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s ADD `display_type` ENUM ('product', 'subcategory') NOT NULL DEFAULT 'product'", $categoryTable);
            try {
                $categoryAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        if (version_compare($moduleVersion, '1.4.8', '<')) {
            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s ADD `type` ENUM ('product', 'category') NOT NULL DEFAULT 'product'", $saleTable);
            try {
                $saleAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table field `category`
            $sql = sprintf("ALTER TABLE %s ADD `category` INT(10) UNSIGNED NOT NULL DEFAULT '0'", $saleTable);
            try {
                $saleAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }

            // Alter table field `percent`
            $sql = sprintf("ALTER TABLE %s ADD `percent` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'", $saleTable);
            try {
                $saleAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        if (version_compare($moduleVersion, '1.5.3', '<')) {
            // Alter table field `percent_partner`
            $sql = sprintf("ALTER TABLE %s ADD `percent_partner` TINYINT(3) UNSIGNED NOT NULL DEFAULT '0'", $promotionTable);
            try {
                $promotionAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
            // Alter table field `price_partner`
            $sql = sprintf("ALTER TABLE %s ADD `price_partner` DECIMAL(16, 2) NOT NULL DEFAULT '0.00'", $promotionTable);
            try {
                $promotionAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
            // Alter table field `partner`
            $sql = sprintf("ALTER TABLE %s ADD `partner` INT(10) UNSIGNED NOT NULL DEFAULT '0', ADD INDEX (`partner`)", $promotionTable);
            try {
                $promotionAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'Table alter query failed: '
                        . $exception->getMessage(),
                ));
                return false;
            }
        }

        if (version_compare($moduleVersion, '1.5.6', '<')) {
            // Add table of discount
            $sql = <<<'EOD'
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
EOD;
            SqlSchema::setType($this->module);
            $sqlHandler = new SqlSchema;
            try {
                $sqlHandler->queryContent($sql);
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status' => false,
                    'message' => 'SQL schema query for author table failed: '
                        . $exception->getMessage(),
                ));

                return false;
            }
        }

        return true;
    }
}