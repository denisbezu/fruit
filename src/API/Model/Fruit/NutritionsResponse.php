<?php

namespace Fruit\API\Model\Fruit;

use Fruit\API\Model\AbstractModel;

class NutritionsResponse extends AbstractModel
{
    /**
     * @var float
     */
    protected $carbohydrates = 0.0;

    /**
     * @var float
     */
    protected $protein = 0.0;

    /**
     * @var float
     */
    protected $fat = 0.0;

    /**
     * @var float
     */
    protected $calories = 0.0;

    /**
     * @var float
     */
    protected $sugar = 0.0;

    /**
     * @return float
     */
    public function getCarbohydrates(): float
    {
        return $this->carbohydrates;
    }

    /**
     * @param float $carbohydrates
     *
     * @return NutritionsResponse
     */
    public function setCarbohydrates(float $carbohydrates): NutritionsResponse
    {
        $this->carbohydrates = $carbohydrates;

        return $this;
    }

    /**
     * @return float
     */
    public function getProtein(): float
    {
        return $this->protein;
    }

    /**
     * @param float $protein
     *
     * @return NutritionsResponse
     */
    public function setProtein(float $protein): NutritionsResponse
    {
        $this->protein = $protein;

        return $this;
    }

    /**
     * @return float
     */
    public function getFat(): float
    {
        return $this->fat;
    }

    /**
     * @param float $fat
     *
     * @return NutritionsResponse
     */
    public function setFat(float $fat): NutritionsResponse
    {
        $this->fat = $fat;

        return $this;
    }

    /**
     * @return float
     */
    public function getCalories(): float
    {
        return $this->calories;
    }

    /**
     * @param float $calories
     *
     * @return NutritionsResponse
     */
    public function setCalories(float $calories): NutritionsResponse
    {
        $this->calories = $calories;

        return $this;
    }

    /**
     * @return float
     */
    public function getSugar(): float
    {
        return $this->sugar;
    }

    /**
     * @param float $sugar
     *
     * @return NutritionsResponse
     */
    public function setSugar(float $sugar): NutritionsResponse
    {
        $this->sugar = $sugar;

        return $this;
    }
}
