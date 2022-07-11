<?php

namespace Fruit\Entity;

use ObjectModel;

class FruitFamily extends ObjectModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $fruit_family;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'fruit_family',
        'primary' => 'id_fruit_family',
        'fields' => [
            'fruit_family' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
            ],
        ],
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'fruit_family' => $this->fruit_family,
        ];
    }
}
