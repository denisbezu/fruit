<?php

namespace Fruit\Grid\Search;

use Fruit\Grid\Definition\Factory\FruitsDefinitionFactory;
use PrestaShop\PrestaShop\Core\Search\Filters;

class FruitFilters extends Filters
{
    protected $filterId = FruitsDefinitionFactory::GRID_ID;

    public static function getDefaults()
    {
        return [
            'limit' => 50,
            'offset' => 0,
            'orderBy' => 'id_fruit',
            'sortOrder' => 'asc',
            'filters' => [],
        ];
    }
}
