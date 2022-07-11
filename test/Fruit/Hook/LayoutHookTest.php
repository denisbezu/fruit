<?php

namespace Fruit\Hook;

use Context;
use Fruit\Entity\Fruit;
use Fruit\Entity\FruitFamily;
use Fruit\Entity\FruitGenus;
use Fruit\Entity\FruitOrder;
use Fruit\Service\FruitService;
use Module;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LayoutHookTest extends KernelTestCase
{
    private $application;

    /**
     * @var Module
     */
    protected $module;

    /**
     * @var HookDispatcher
     */
    protected $hookDispatcher;

    /**
     * @var FruitService
     */
    protected $fruitService;

    protected function setUp()
    {
        parent::setUp();
        self::bootKernel(['environment' => 'prod']);
        $this->application = new Application(static::$kernel);

        $this->module = $this->application->getKernel()->getContainer()->get('fruit');
        $this->hookDispatcher = new HookDispatcher($this->module);
        $this->fruitService = $this->application->getKernel()->getContainer()->get('fruit.service.fruit_service');

        $this->clearData();
    }

    /**
     * @dataProvider getDisplayHomeDataProvider
     *
     * @return void
     */
    public function testDisplayHome($syncData, $isResultEmpty)
    {
        Context::getContext()->employee = new \Employee(43);
        $this->clearData();
        if ($syncData) {
            $this->fruitService->synchronizeFruits();
        }
        $frontController = new \FrontController();
        Context::getContext()->controller = $frontController;

        $result = $this->hookDispatcher->dispatch('displayHome', []);
        if ($isResultEmpty) {
            $this->assertEmpty($result);
        } else {
            $this->assertNotEmpty($result);
        }
    }

    public function getDisplayHomeDataProvider()
    {
        return [
            [true, false],
            [false, true],
        ];
    }

    protected function clearData()
    {
        \Db::getInstance()->delete(Fruit::$definition['table'], 1);
        \Db::getInstance()->delete(FruitGenus::$definition['table'], 1);
        \Db::getInstance()->delete(FruitFamily::$definition['table'], 1);
        \Db::getInstance()->delete(FruitOrder::$definition['table'], 1);
    }
}
