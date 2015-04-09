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
        $moduleVersion  = $e->getParam('version');
        
        // Set product model
        $productModel    = Pi::model('product', $this->module);
        $productTable    = $productModel->getTable();
        $productAdapter  = $productModel->getAdapter();

        // Set category model
        $categoryModel    = Pi::model('category', $this->module);
        $categoryTable    = $categoryModel->getTable();
        $categoryAdapter  = $categoryModel->getAdapter();

        // Set field model
        $fieldModel    = Pi::model('field', $this->module);
        $fieldTable    = $fieldModel->getTable();
        $fieldAdapter  = $fieldModel->getAdapter();

        // Update to version 0.3.0
        if (version_compare($moduleVersion, '0.3.0', '<')) {
            // Alter table field `type`
            $sql = sprintf("ALTER TABLE %s ADD `brand` int(10) unsigned NOT NULL default '0'", $productTable);
            try {
                $productAdapter->query($sql, 'execute');
            } catch (\Exception $exception) {
                $this->setResult('db', array(
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
                                   . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.0.5
        if (version_compare($moduleVersion, '1.0.5', '<')) {
            
            // Add table of field_category
            $sql =<<<'EOD'
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
                    'status'    => false,
                    'message'   => 'SQL schema query for author table failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
                                   . $exception->getMessage(),
                ));
                return false;
            }
        }

        // Update to version 1.0.8
        if (version_compare($moduleVersion, '1.0.8', '<')) {
            // Add table of field_position
            $sql =<<<'EOD'
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
                    'status'    => false,
                    'message'   => 'SQL schema query for author table failed: '
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
                    'status'    => false,
                    'message'   => 'Table alter query failed: '
                                   . $exception->getMessage(),
                ));
                return false;
            }
        }

        return true;
    }    
}