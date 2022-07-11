<?php

namespace Fruit\Service;

use Fruit\API\Client;
use Fruit\API\Model\ArrayCollection;
use Fruit\API\Model\Fruit\FruitModel;
use Fruit\API\Request\FruitsRequest;
use Fruit\API\Response\AbstractResponse;
use Fruit\Entity\Fruit;
use Fruit\Entity\FruitFamily;
use Fruit\Entity\FruitGenus;
use Fruit\Entity\FruitOrder;
use Fruit\Model\FruitDisplayModel;
use Fruit\Repository\FruitRepository;
use PrestaShopLogger;

class FruitService
{
    /**
     * @var FruitRepository
     */
    protected $fruitRepository;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param FruitRepository $fruitRepository
     */
    public function __construct(FruitRepository $fruitRepository)
    {
        $this->fruitRepository = $fruitRepository;
        $this->client = new Client();
    }

    /**
     * @return bool
     */
    public function synchronizeFruits()
    {
        /** @var ArrayCollection<FruitModel> $fruits */
        $fruits = $this->getFruitsFromAPI();

        $result = true;

        foreach ($fruits as $fruit) {
            $result &= $this->createFromModel($fruit);
        }

        return $result;
    }

    public function getAll()
    {
        $fruits = [];
        $dbFruits = $this->fruitRepository->getAllPretty();

        if (empty($dbFruits)) {
            return [];
        }

        foreach ($dbFruits as $dbFruit) {
            $fruits[] = (new FruitDisplayModel())
                ->setId($dbFruit['id_fruit'])
                ->setName($dbFruit['name'])
                ->setFamily($dbFruit['family'])
                ->setGenus($dbFruit['genus'])
                ->setOrder($dbFruit['order'])
                ->setDateAdd($dbFruit['date_add']);
        }

        return $fruits;
    }

    protected function createFromModel(FruitModel $fruitModel)
    {
        try {
            $idFruit = $this->findByExternalId($fruitModel->getId());
            if (!empty($idFruit)) {
                return true;
            }

            $idGenus = $this->createGenusIfNotExist($fruitModel->getGenus());
            $idOrder = $this->createFruitOrderIfNotExist($fruitModel->getOrder());
            $idFamily = $this->createFamilyIfNotExist($fruitModel->getFamily());

            $fruit = new Fruit();
            $fruit->id_fruit_genus = $idGenus;
            $fruit->id_fruit_order = $idOrder;
            $fruit->id_fruit_family = $idFamily;
            $fruit->name = $fruitModel->getName();
            $fruit->id_external = $fruitModel->getId();

            $fruit->carbohydrates = $fruitModel->getNutritions()->getCarbohydrates();
            $fruit->protein = $fruitModel->getNutritions()->getProtein();
            $fruit->fat = $fruitModel->getNutritions()->getFat();
            $fruit->calories = $fruitModel->getNutritions()->getCalories();
            $fruit->sugar = $fruitModel->getNutritions()->getSugar();

            $fruit->save();

            return true;
        } catch (\Throwable $e) {
            $message = sprintf(
                '[fruit] Cannot create model %s. Error message: %s',
                $fruitModel->getId(),
                $e->getMessage()
            );

            PrestaShopLogger::addLog($message);

            return false;
        }
    }

    protected function createGenusIfNotExist(string $genus)
    {
        $idGenus = $this->findGenusByName($genus);

        if (!empty($idGenus)) {
            return (int) $idGenus;
        }

        $genusObj = new FruitGenus();
        $genusObj->fruit_genus = $genus;
        $genusObj->save();

        return $genusObj->id;
    }

    protected function createFamilyIfNotExist(string $family)
    {
        $idFamily = $this->findFruitFamilyByName($family);

        if (!empty($idFamily)) {
            return (int) $idFamily;
        }

        $familyObj = new FruitFamily();
        $familyObj->fruit_family = $family;
        $familyObj->save();

        return $familyObj->id;
    }

    protected function createFruitOrderIfNotExist(string $fruitOrder)
    {
        $idOrder = $this->findFruitOrderByName($fruitOrder);

        if (!empty($idOrder)) {
            return (int) $idOrder;
        }

        $order = new FruitOrder();
        $order->fruit_order = $fruitOrder;
        $order->save();

        return $order->id;
    }

    protected function getFruitsFromAPI()
    {
        $request = new FruitsRequest();
        /** @var AbstractResponse $response */
        $response = $this->client->sendRequest($request);

        return $response->getModel();
    }

    protected function findByExternalId(int $externalId)
    {
        return $this->fruitRepository->findByExternlaId($externalId);
    }

    protected function findGenusByName(string $genus)
    {
        return $this->fruitRepository->findGenusByName($genus);
    }

    protected function findFruitOrderByName(string $fruitOrder)
    {
        return $this->fruitRepository->findFruitOrderByName($fruitOrder);
    }

    protected function findFruitFamilyByName(string $family)
    {
        return $this->fruitRepository->findFruitFamilyByName($family);
    }
}
