<?php

namespace Fruit\Entity;

use ObjectModel;

class Fruit extends ObjectModel
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var int
     */
    public $id_fruit;

    /**
     * @var int
     */
    public $id_external;

    /**
     * @var int
     */
    public $id_fruit_family;

    /**
     * @var int
     */
    public $id_fruit_order;

    /**
     * @var int
     */
    public $id_fruit_genus;

    /**
     * @var float
     */
    public $carbohydrates = 0.0;

    /**
     * @var float
     */
    public $protein = 0.0;

    /**
     * @var float
     */
    public $fat = 0.0;

    /**
     * @var float
     */
    public $calories = 0.0;

    /**
     * @var float
     */
    public $sugar = 0.0;

    /**
     * @var string
     */
    public $date_add;

    /**
     * @var array
     */
    public static $definition = [
        'table' => 'fruit',
        'primary' => 'id_fruit',
        'fields' => [
            'id_external' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'name' => [
                'type' => self::TYPE_STRING,
                'validate' => 'isString',
            ],
            'id_fruit_order' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'id_fruit_genus' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'id_fruit_family' => [
                'type' => self::TYPE_INT,
                'validate' => 'isUnsignedInt',
            ],
            'carbohydrates' => [
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat',
            ],
            'protein' => [
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat',
            ],
            'fat' => [
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat',
            ],
            'calories' => [
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat',
            ],
            'sugar' => [
                'type' => self::TYPE_FLOAT,
                'validate' => 'isFloat',
            ],
            'date_add' => [
                'type' => self::TYPE_DATE,
                'validate' => 'isDate',
                'copy_post' => false,
            ],
        ],
    ];

    public function toArray()
    {
        return [
            'id' => $this->id,
            'id_external' => $this->id_external,
            'name' => $this->name,
            'id_fruit_family' => $this->id_fruit_family,
            'id_fruit_order' => $this->id_fruit_order,
            'id_fruit_genus' => $this->id_fruit_genus,
            'carbohydrates' => $this->carbohydrates,
            'protein' => $this->protein,
            'fat' => $this->fat,
            'calories' => $this->calories,
            'sugar' => $this->sugar,
            'date_add' => $this->date_add,
        ];
    }
}
