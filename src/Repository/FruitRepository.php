<?php

namespace Fruit\Repository;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\FetchMode;
use Fruit\Entity\Fruit;
use Fruit\Entity\FruitFamily;
use Fruit\Entity\FruitGenus;
use Fruit\Entity\FruitOrder;

class FruitRepository
{
    /**
     * @var Connection
     */
    private $connection;

    /**`
     * @var string $dbPrefix
     */
    private $dbPrefix;

    /**
     * @var int
     */
    private $idLang;

    /**
     * @var int
     */
    private $idShop;

    /**
     * FruitRepository constructor.
     *
     * @param Connection $connection
     * @param string $dbPrefix
     * @param int $idLang
     * @param int $idShop
     */
    public function __construct(Connection $connection,
                                string $dbPrefix,
                                int $idLang,
                                int $idShop)
    {
        $this->connection = $connection;
        $this->dbPrefix = $dbPrefix;
        $this->idLang = $idLang;
        $this->idShop = $idShop;
    }

    public function findByExternlaId(int $externalId)
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select(Fruit::$definition['primary'])
            ->from($this->dbPrefix . Fruit::$definition['table'])
            ->where('`id_external` = :idExternal')
            ->setParameter('idExternal', $externalId)
            ->execute()
            ->fetch(FetchMode::COLUMN);
    }

    public function findGenusByName(string $genus)
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select(FruitGenus::$definition['primary'])
            ->from($this->dbPrefix . FruitGenus::$definition['table'])
            ->where('`fruit_genus` = ' . $qb->expr()->literal($genus))
            ->execute()
            ->fetch(FetchMode::COLUMN);
    }

    public function findFruitOrderByName(string $fruitOrder)
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select(FruitOrder::$definition['primary'])
            ->from($this->dbPrefix . FruitOrder::$definition['table'])
            ->where('`fruit_order` = ' . $qb->expr()->literal($fruitOrder))
            ->execute()
            ->fetch(FetchMode::COLUMN);
    }

    public function findFruitFamilyByName(string $family)
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select(FruitFamily::$definition['primary'])
            ->from($this->dbPrefix . FruitFamily::$definition['table'])
            ->where('`fruit_family` = ' . $qb->expr()->literal($family))
            ->execute()
            ->fetch(FetchMode::COLUMN);
    }

    public function getAllPretty()
    {
        $qb = $this->connection->createQueryBuilder();

        return $qb->select('f.`id_fruit`, 
                            f.`name`, 
                            ff.`fruit_family` AS family, 
                            fo.`fruit_order` AS `order`, 
                            fg.`fruit_genus` AS genus,
                            f.`date_add`')->from($this->dbPrefix . Fruit::$definition['table'], 'f')
            ->leftJoin(
                'f',
                $this->dbPrefix . FruitFamily::$definition['table'],
                'ff',
                'ff.`id_fruit_family` = f.`id_fruit_family`'
            )
            ->leftJoin(
                'f',
                $this->dbPrefix . FruitOrder::$definition['table'],
                'fo',
                'fo.`id_fruit_order` = f.`id_fruit_order`'
            )
            ->leftJoin(
                'f',
                $this->dbPrefix . FruitGenus::$definition['table'],
                'fg',
                'fg.`id_fruit_genus` = f.`id_fruit_genus`'
            )
            ->execute()
            ->fetchAll();
    }
}
