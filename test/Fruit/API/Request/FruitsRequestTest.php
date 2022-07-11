<?php

namespace Fruit\API\Request;

use Fruit\API\Client;
use Fruit\API\Model\ArrayCollection;
use PHPUnit\Framework\TestCase;

class FruitsRequestTest extends TestCase
{
    public function testFruitsOK()
    {
        $client = new Client();
        $request = new FruitsRequest();

        $response = $client->sendRequest($request);

        $model = $response->getModel();

        $this->assertInstanceOf(ArrayCollection::class, $model);
        $this->assertNotEmpty(count($model));
    }
}
