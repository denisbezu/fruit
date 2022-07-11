<?php

namespace Fruit\Model;

class FruitDisplayModel
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $family;

    /**
     * @var string
     */
    protected $order;

    /**
     * @var string
     */
    protected $genus;

    /**
     * @var string
     */
    protected $dateAdd;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return FruitDisplayModel
     */
    public function setId(int $id): FruitDisplayModel
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return FruitDisplayModel
     */
    public function setName(string $name): FruitDisplayModel
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getFamily(): string
    {
        return $this->family;
    }

    /**
     * @param string $family
     *
     * @return FruitDisplayModel
     */
    public function setFamily(string $family): FruitDisplayModel
    {
        $this->family = $family;

        return $this;
    }

    /**
     * @return string
     */
    public function getOrder(): string
    {
        return $this->order;
    }

    /**
     * @param string $order
     *
     * @return FruitDisplayModel
     */
    public function setOrder(string $order): FruitDisplayModel
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return string
     */
    public function getGenus(): string
    {
        return $this->genus;
    }

    /**
     * @param string $genus
     *
     * @return FruitDisplayModel
     */
    public function setGenus(string $genus): FruitDisplayModel
    {
        $this->genus = $genus;

        return $this;
    }

    /**
     * @return string
     */
    public function getDateAdd(): string
    {
        return $this->dateAdd;
    }

    /**
     * @param string $dateAdd
     *
     * @return FruitDisplayModel
     */
    public function setDateAdd(string $dateAdd): FruitDisplayModel
    {
        $this->dateAdd = $dateAdd;

        return $this;
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'family' => $this->getFamily(),
            'order' => $this->getOrder(),
            'genus' => $this->getGenus(),
            'date_add' => $this->getDateAdd(),
        ];
    }
}
