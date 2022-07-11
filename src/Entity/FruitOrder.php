<?php

namespace Fruit\Entity;

use ObjectModel;

class FruitOrder extends ObjectModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $fruit_order;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'fruit_order',
        'primary' => 'id_fruit_order',
        'fields' => [
            'fruit_order' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
            ],
        ],
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'fruit_order' => $this->fruit_order,
        ];
    }
}
