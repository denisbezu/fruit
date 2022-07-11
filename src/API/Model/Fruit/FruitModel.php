<?php

namespace Fruit\API\Model\Fruit;

use Fruit\API\Model\AbstractModel;

class FruitModel extends AbstractModel
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $genus;

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
     * @var NutritionsResponse
     */
    protected $nutritions;

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
     * @return FruitModel
     */
    public function setId(int $id): FruitModel
    {
        $this->id = $id;

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
     * @return FruitModel
     */
    public function setGenus(string $genus): FruitModel
    {
        $this->genus = $genus;

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
     * @return FruitModel
     */
    public function setName(string $name): FruitModel
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
     * @return FruitModel
     */
    public function setFamily(string $family): FruitModel
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
     * @return FruitModel
     */
    public function setOrder(string $order): FruitModel
    {
        $this->order = $order;

        return $this;
    }

    /**
     * @return NutritionsResponse
     */
    public function getNutritions(): NutritionsResponse
    {
        return $this->nutritions;
    }

    /**
     * @param array $nutritions
     *
     * @return FruitModel
     */
    public function setNutritions(array $nutritions): FruitModel
    {
        $this->nutritions = (new NutritionsResponse())->hydrate($nutritions);

        return $this;
    }
}
