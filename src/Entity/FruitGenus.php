<?php

namespace Fruit\Entity;

use ObjectModel;

class FruitGenus extends ObjectModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $fruit_genus;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'fruit_genus',
        'primary' => 'id_fruit_genus',
        'fields' => [
            'fruit_genus' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
            ],
        ],
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'fruit_genus' => $this->fruit_genus,
        ];
    }
}
