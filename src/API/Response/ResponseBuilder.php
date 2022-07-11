<?php

namespace Fruit\API\Response;

use InvalidArgumentException;

/**
 * Response Builder
 *
 * Build a PSR-7 Response object
 */
class ResponseBuilder
{
    /**
     * PSR-7 Response
     *
     * @var AbstractResponse
     */
    protected $response;

    /**
     * Create a Response Builder
     *
     * @param AbstractResponse $response
     */
    public function __construct(AbstractResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Return the response
     *
     * @return AbstractResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Set the response
     *
     * @param AbstractResponse $response Response object
     *
     * @return void
     */
    public function setResponse(AbstractResponse $response)
    {
        $this->response = $response;
    }

    /**
     * Add response header from header line string
     *
     * @param string $headerLine Response header line string
     *
     * @return self $this
     *
     * @throws InvalidArgumentException Invalid header line argument
     */
    public function addHeader($headerLine)
    {
        $headerParts = explode(':', $headerLine, 2);

        if (count($headerParts) !== 2) {
            throw new InvalidArgumentException("'$headerLine' is not a valid HTTP header line");
        }

        $headerName = trim($headerParts[0]);
        $headerValue = trim($headerParts[1]);

        if ($this->response->hasHeader($headerName)) {
            $this->response = $this->response->withAddedHeader($headerName, $headerValue);
        } else {
            $this->response = $this->response->withHeader($headerName, $headerValue);
        }

        return $this;
    }

    /**
     * Set response headers from header line array
     *
     * @param array<string> $headers Array of header lines
     *
     * @return self $this
     *
     * @throws InvalidArgumentException Invalid status code argument value
     */
    public function setHeadersFromArray(array $headers)
    {
        $status = (string) array_shift($headers);

        $this->setStatus($status);

        foreach ($headers as $header) {
            $header_line = trim($header);

            if ($header_line === '') {
                continue;
            }

            $this->addHeader($header_line);
        }

        return $this;
    }

    /**
     * Set reponse status
     *
     * @param string $statusLine Response status line string
     *
     * @return self $this
     *
     * @throws InvalidArgumentException Invalid status line argument
     */
    public function setStatus($statusLine)
    {
        $statusParts = explode(' ', $statusLine, 3);
        $partsCount = count($statusParts);

        if ($partsCount < 2 || strpos(strtoupper($statusParts[0]), 'HTTP/') !== 0) {
            throw new InvalidArgumentException("'$statusLine' is not a valid HTTP status line");
        }

        $reasonPhrase = ($partsCount > 2 ? $statusParts[2] : '');

        $this->response = $this->response
            ->withStatus((int) $statusParts[1], $reasonPhrase)
            ->withProtocolVersion(substr($statusParts[0], 5));

        return $this;
    }
}
