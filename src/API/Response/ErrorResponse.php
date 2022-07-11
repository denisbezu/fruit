<?php

namespace Fruit\API\Response;

use Fruit\API\Model\Error;

class ErrorResponse extends AbstractResponse
{
    /**
     * @inherit
     */
    public function getModel()
    {
        return new Error();
    }
}
