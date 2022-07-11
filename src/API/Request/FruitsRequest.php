<?php

namespace Fruit\API\Request;

use Fruit\API\Response\FruitsResponse;

class FruitsRequest extends AbstractRequest
{
    protected $method = 'GET';

    protected $requestTarget = '/api/fruit/all';

    protected $response = FruitsResponse::class;
}
