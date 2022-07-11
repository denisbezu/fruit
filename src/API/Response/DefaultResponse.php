<?php

namespace Fruit\API\Response;

use Fruit\API\Model\Error;

/**
 * Default Response
 */
class DefaultResponse extends AbstractResponse
{
    /**
     * Get isntance of a response
     *
     * @param string $classname
     *
     * @return DefaultResponse
     */
    public static function getInstance($classname)
    {
        return new $classname();
    }

    /**
     * @inherit
     */
    public function getModel()
    {
        return new Error();
    }
}
