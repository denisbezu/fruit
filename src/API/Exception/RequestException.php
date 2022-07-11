<?php

namespace Fruit\API\Exception;

use Exception;
use Psr\Http\Message\RequestInterface;

class RequestException extends \RuntimeException
{
    /**
     * Request object
     *
     * @var RequestInterface
     */
    private $request;

    /**
     * Create request exception object
     *
     * @param string $message Exception message
     * @param RequestInterface $request Request object
     * @param \Exception|null $lastException Previous exception object
     */
    public function __construct($message, RequestInterface $request, Exception $lastException = null)
    {
        $this->request = $request;

        parent::__construct($message, 0, $lastException);
    }

    /**
     * Get the request object
     *
     * @return RequestInterface
     */
    public function getRequest()
    {
        return $this->request;
    }
}
