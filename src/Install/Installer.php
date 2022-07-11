<?php

namespace Fruit\Install;

use Db;
use Fruit\Constant\Database;
use Fruit\Constant\ModuleTabs;
use Language;
use PrestaShopBundle\Entity\Repository\TabRepository;
use PrestaShopLogger;
use Psr\Container\ContainerInterface;
use Tab;
use Validate;

class Installer
{
    /** @var ContainerInterface */
    protected $container;

    /** @var Db */
    protected $db;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->db = Db::getInstance();
    }

    public function installTabs()
    {
        return $this->installTabFruits();
    }

    public function uninstallTabs()
    {
        return $this->uninstallTabFruits();
    }

    public function createTables()
    {
        return $this->createFruitFamilyTable()
            && $this->createFruitOrderTable()
            && $this->createFruitGenusTable()
            && $this->createFruitTable();
    }

    public function removeTables()
    {
        return $this->dropTable($this->db->getPrefix() . Database::TABLE_FRUIT_ORDER)
            && $this->dropTable($this->db->getPrefix() . Database::TABLE_FRUIT_GENUS)
            && $this->dropTable($this->db->getPrefix() . Database::TABLE_FRUIT_FAMILY)
            && $this->dropTable($this->db->getPrefix() . Database::TABLE_FRUIT);
    }

    protected function createFruitFamilyTable()
    {
        $query = sprintf('
            CREATE TABLE IF NOT EXISTS %s 
            (
                `id_fruit_family` INT(11) AUTO_INCREMENT,
                `fruit_family` VARCHAR(255),
                PRIMARY KEY (`id_fruit_family`),
                UNIQUE (family)
            ) 
            ENGINE="%s" CHARSET="%s" COLLATE="%s";
            ',
            $this->db->getPrefix() . Database::TABLE_FRUIT_FAMILY,
            $this->db->getBestEngine(),
            Database::CHARSET,
            Database::COLLATE
        );
        try {
            return $this->db->execute($query);
        } catch (\Throwable $e) {
            $message = sprintf(
                '[fruit] Can not create table %s. Error message: %s',
                $this->db->getPrefix() . Database::TABLE_FRUIT_FAMILY,
                $e->getMessage()
            );
            $this->log($message);

            return false;
        }
    }

    protected function createFruitOrderTable()
    {
        $query = sprintf('
            CREATE TABLE IF NOT EXISTS %s 
            (
                `id_fruit_order` INT(11) AUTO_INCREMENT,
                `fruit_order` VARCHAR(255),
                PRIMARY KEY (`id_fruit_order`),
                UNIQUE (fruit_order)
            ) 
            ENGINE="%s" CHARSET="%s" COLLATE="%s";
            ',
            $this->db->getPrefix() . Database::TABLE_FRUIT_ORDER,
            $this->db->getBestEngine(),
            Database::CHARSET,
            Database::COLLATE
        );
        try {
            return $this->db->execute($query);
        } catch (\Throwable $e) {
            $message = sprintf(
                '[fruit] Can not create table %s. Error message: %s',
                $this->db->getPrefix() . Database::TABLE_FRUIT_ORDER,
                $e->getMessage()
            );
            $this->log($message);

            return false;
        }
    }

    protected function createFruitGenusTable()
    {
        $query = sprintf('
            CREATE TABLE IF NOT EXISTS %s 
            (
                `id_fruit_genus` INT(11) AUTO_INCREMENT,
                `fruit_genus` VARCHAR(255),
                PRIMARY KEY (`id_fruit_genus`),
                UNIQUE (fruit_genus)
            ) 
            ENGINE="%s" CHARSET="%s" COLLATE="%s";
            ',
            $this->db->getPrefix() . Database::TABLE_FRUIT_GENUS,
            $this->db->getBestEngine(),
            Database::CHARSET,
            Database::COLLATE
        );
        try {
            return $this->db->execute($query);
        } catch (\Throwable $e) {
            $message = sprintf(
                '[fruit] Can not create table %s. Error message: %s',
                $this->db->getPrefix() . Database::TABLE_FRUIT_GENUS,
                $e->getMessage()
            );
            $this->log($message);

            return false;
        }
    }

    protected function createFruitTable()
    {
        $query = sprintf('
            CREATE TABLE IF NOT EXISTS %s 
            (
                `id_fruit` INT(11) AUTO_INCREMENT,
                `name` VARCHAR(255),
                `id_external` INT(11),
                `id_fruit_family` INT(11),
                `id_fruit_order` INT(11),
                `id_fruit_genus` INT(11),
                `carbohydrates` DECIMAL(10, 2) DEFAULT 0,
                `protein` DECIMAL(10, 2) DEFAULT 0,
                `fat` DECIMAL(10, 2) DEFAULT 0,
                `calories` DECIMAL(10, 2) DEFAULT 0,
                `sugar` DECIMAL(10, 2) DEFAULT 0,
                `date_add` DATETIME DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_fruit`),
                UNIQUE(id_external),
                FOREIGN KEY (id_fruit_family) REFERENCES %s(id_fruit_family),
                FOREIGN KEY (id_fruit_genus) REFERENCES %s(id_fruit_genus),
                FOREIGN KEY (id_fruit_order) REFERENCES %s(id_fruit_order)
            ) 
            ENGINE="%s" CHARSET="%s" COLLATE="%s";
            ',
            $this->db->getPrefix() . Database::TABLE_FRUIT,
            $this->db->getPrefix() . Database::TABLE_FRUIT_FAMILY,
            $this->db->getPrefix() . Database::TABLE_FRUIT_GENUS,
            $this->db->getPrefix() . Database::TABLE_FRUIT_ORDER,
            $this->db->getBestEngine(),
            Database::CHARSET,
            Database::COLLATE
        );
        try {
            return $this->db->execute($query);
        } catch (\Throwable $e) {
            $message = sprintf(
                '[fruit] Can not create table %s. Error message: %s',
                $this->db->getPrefix() . Database::TABLE_FRUIT,
                $e->getMessage()
            );
            $this->log($message);

            return false;
        }
    }

    protected function dropTable($table)
    {
        $query = sprintf('DROP TABLE `%s`', pSQL($table));
        try {
            return $this->db->execute($query);
        } catch (\Throwable $e) {
            $message = sprintf(
                '[fruit] Can not drop table %s. Error message: %s',
                $table,
                $e->getMessage()
            );
            $this->log($message);

            return false;
        }
    }

    protected function installTabFruits()
    {
        $tab = Tab::getInstanceFromClassName(ModuleTabs::FRUITS);
        if (Validate::isLoadedObject($tab)) {
            return true;
        }
        $parent = $this->getTabRepository()->findOneByClassName('IMPROVE');
        $tab->class_name = ModuleTabs::FRUITS;
        $tab->active = true;
        $tab->module = 'fruit';
        $tab->id_parent = empty($parent->getId()) ? 0 : $parent->getId();
        $tab->name = [];
        foreach (Language::getLanguages(false) as $lang) {
            $tab->name[$lang['id_lang']] = 'Fruits';
        }

        return $tab->save();
    }

    protected function uninstallTabFruits()
    {
        return $this->uninstallByClassName(ModuleTabs::FRUITS);
    }

    protected function uninstallByClassName($className)
    {
        $tab = Tab::getInstanceFromClassName($className);
        if (Validate::isLoadedObject($tab)) {
            return true;
        }

        $tab->delete();

        return true;
    }

    /**
     * @return TabRepository
     */
    protected function getTabRepository()
    {
        return $this->container->get('prestashop.core.admin.tab.repository');
    }

    protected function log($message)
    {
        if (is_string($message)) {
            PrestaShopLogger::addLog($message);
        }
    }
}
