<?php

namespace Fruit\API\Request;

use Fruit\API\Model\AbstractModel;
use Fruit\API\Stream;
use Fruit\API\Uri\ProductionUri;
use JsonSerializable;
use Psr\Http\Message\RequestInterface;

abstract class AbstractRequest implements RequestInterface, JsonSerializable
{
    use MessageTrait;
    use RequestTrait;

    /**
     * @var AbstractModel
     */
    private $body;

    /**
     * @var string
     */
    protected $response;

    /**
     * @param array<string> $headers Request headers
     * @param string $version protocol version
     */
    public function __construct(array $headers = [], $version = '1.1')
    {
        $this->uri = new ProductionUri();
        $this->uri = $this->uri->withPath($this->requestTarget);
        $this->setHeaders($headers);

        $this->protocol = $version;

        if (!$this->hasHeader('Host')) {
            $this->updateHostFromUri();
        }

        // initialization of the stream until Request::getBody()
        $this->stream = Stream::create('');
    }

    /**
     * Set Body From Model
     *
     * @param AbstractModel $body
     *
     * @return self
     */
    public function setModel(AbstractModel $body)
    {
        $json = json_encode($body->jsonSerialize(), JSON_PRETTY_PRINT);
        if (JSON_ERROR_NONE !== json_last_error()) {
            throw new \InvalidArgumentException('json_encode error: ' . json_last_error_msg());
        }
        $new = clone $this;
        $new->stream = Stream::create((string) $json);

        return $new;
    }

    /**
     * Set Body From Model
     *
     * @return string
     */
    public function getResponseObject()
    {
        return $this->response;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}
