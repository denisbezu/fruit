<?php

namespace Fruit\Service;

use Fruit\Entity\Fruit;
use Fruit\Entity\FruitFamily;
use Fruit\Entity\FruitGenus;
use Fruit\Entity\FruitOrder;
use Fruit\Model\FruitDisplayModel;
use PrestaShopCollection;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FruitServiceTest extends KernelTestCase
{
    private $application;

    /**
     * @var FruitService
     */
    protected $fruitService;

    public function setUp()
    {
        parent::setUp();
        self::bootKernel(['environment' => 'prod']);
        $this->application = new Application(static::$kernel);
        $this->fruitService = $this->application->getKernel()->getContainer()->get('fruit.service.fruit_service');
    }

    public function testSynchronizeFruits()
    {
        $this->clearData();

        $this->fruitService->synchronizeFruits();

        $fruitCollection = new PrestaShopCollection(Fruit::class);

        $this->assertGreaterThan(0, $fruitCollection->count());
    }

    /**
     * @depends testSynchronizeFruits
     */
    public function testGetAll()
    {
        $fruits = $this->fruitService->getAll();

        $this->assertNotEmpty($fruits);
        $this->assertInstanceOf(FruitDisplayModel::class, $fruits[0]);
    }

    protected function clearData()
    {
        \Db::getInstance()->delete(Fruit::$definition['table'], 1);
        \Db::getInstance()->delete(FruitGenus::$definition['table'], 1);
        \Db::getInstance()->delete(FruitFamily::$definition['table'], 1);
        \Db::getInstance()->delete(FruitOrder::$definition['table'], 1);
    }
}
