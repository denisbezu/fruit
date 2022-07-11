<?php

namespace Fruit\Grid\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Fruit\Entity\Fruit;
use Fruit\Entity\FruitFamily;
use Fruit\Entity\FruitGenus;
use Fruit\Entity\FruitOrder;
use PrestaShop\PrestaShop\Core\Grid\Query\AbstractDoctrineQueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Query\DoctrineSearchCriteriaApplicatorInterface;
use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteriaInterface;

class FruitsQueryBuilder extends AbstractDoctrineQueryBuilder
{
    /**
     * @var DoctrineSearchCriteriaApplicatorInterface
     */
    private $searchCriteriaApplicator;

    /**
     * @param Connection $connection
     * @param string $dbPrefix
     * @param DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator
     */
    public function __construct(Connection $connection,
                                string $dbPrefix,
                                DoctrineSearchCriteriaApplicatorInterface $searchCriteriaApplicator)
    {
        parent::__construct($connection, $dbPrefix);
        $this->searchCriteriaApplicator = $searchCriteriaApplicator;
    }

    public function getSearchQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        $qb = $this->getQueryBuilder($searchCriteria->getFilters());

        $qb->select('f.`id_fruit`, 
                            f.`name`, 
                            ff.`fruit_family` AS family, 
                            fo.`fruit_order` AS `order`, 
                            fg.`fruit_genus` AS genus,
                            f.`date_add`');

        $this->searchCriteriaApplicator
            ->applyPagination($searchCriteria, $qb)
            ->applySorting($searchCriteria, $qb);

        return $qb;
    }

    public function getCountQueryBuilder(SearchCriteriaInterface $searchCriteria)
    {
        return $this->getQueryBuilder($searchCriteria->getFilters())
            ->select('COUNT(DISTINCT f.`id_fruit`)');
    }

    /**
     * Gets query builder with the common sql for blog post listing.
     *
     * @param array $filters
     *
     * @return QueryBuilder
     */
    private function getQueryBuilder(array $filters)
    {
        $availableFilters = [
            'id_fruit',
            'name',
            'family',
            'order',
            'genus',
            'date_add',
        ];

        $qb = $this->connection
            ->createQueryBuilder()
            ->from($this->dbPrefix . Fruit::$definition['table'], 'f')
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
            );

        foreach ($filters as $filterName => $value) {
            if (!in_array($filterName, $availableFilters, true)) {
                continue;
            }

            if (in_array($filterName, ['id_fruit'], true)) {
                $qb->andWhere('f.`' . $filterName . '` = :' . $filterName);
                $qb->setParameter($filterName, $value);

                continue;
            }

            if (in_array($filterName, ['family'], true)) {
                $qb->andWhere('ff.`fruit_family` LIKE :' . $filterName);
                $qb->setParameter($filterName, '%' . $value . '%');

                continue;
            }

            if (in_array($filterName, ['order'], true)) {
                $qb->andWhere('fo.`fruit_order` LIKE :' . $filterName);
                $qb->setParameter($filterName, '%' . $value . '%');

                continue;
            }

            if (in_array($filterName, ['genus'], true)) {
                $qb->andWhere('fg.`fruit_genus` LIKE :' . $filterName);
                $qb->setParameter($filterName, '%' . $value . '%');

                continue;
            }

            if ('date_add' === $filterName) {
                if (isset($value['from'])) {
                    $qb->andWhere('f.date_add >= :date_add_from');
                    $qb->setParameter('date_add_from', sprintf('%s %s', $value['from'], '0:0:0'));
                }

                if (isset($value['to'])) {
                    $qb->andWhere('f.date_add <= :date_add_to');
                    $qb->setParameter('date_add_to', sprintf('%s %s', $value['to'], '23:59:59'));
                }

                continue;
            }

            $qb->andWhere('fl.`' . $filterName . '` LIKE :' . $filterName);
            $qb->setParameter($filterName, '%' . $value . '%');
        }

        return $qb;
    }
}
