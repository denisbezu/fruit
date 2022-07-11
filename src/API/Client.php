<?php

namespace Fruit\API;

use Fruit\API\Exception\RequestException;
use Fruit\API\Request\AbstractRequest;
use Fruit\API\Response\DefaultResponse;
use Fruit\API\Response\ResponseBuilder;
use InvalidArgumentException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use UnexpectedValueException;

/**
 * API client
 */
class Client
{
    /**
     * @var Stream
     */
    private $stream;

    /**
     * cURL handler
     *
     * @var resource|\CurlHandle
     */
    protected $ch;

    /**
     * cURL options array
     *
     * @var array<mixed>
     */
    protected $options;

    /**
     * Maximum request body size
     *
     * @var int
     */
    protected static $MAX_BODY_SIZE;

    protected $lastRequest = null;

    /**
     * Create new cURL http client object
     */
    public function __construct()
    {
        self::$MAX_BODY_SIZE = 1024 * 1024;
    }

    /**
     * Send a PSR-7 Request
     *
     * @param AbstractRequest $request
     *
     * @return ResponseInterface
     *
     * @throws RequestException Invalid request
     * @throws InvalidArgumentException Invalid header names and/or values
     * @throws RuntimeException Failure to create stream
     */
    public function sendRequest(AbstractRequest $request)
    {
        $this->lastRequest = null;

        $response = $this->createResponse($request);
        $options = $this->createOptions($request, $response);
        $this->ch = curl_init();

        curl_setopt_array($this->ch, $options);

        $this->lastRequest = $request;
        // Execute the request
        $result = curl_exec($this->ch);

        $infos = curl_getinfo($this->ch);
        // Check for any request errors
        switch (curl_errno($this->ch)) {
            case CURLE_OK:
                break;
            case CURLE_COULDNT_RESOLVE_PROXY:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_COULDNT_CONNECT:
            case CURLE_OPERATION_TIMEOUTED:
            case CURLE_SSL_CONNECT_ERROR:
                throw new RequestException('curl error ' . curl_error($this->ch), $request);
            default:
                throw new RequestException('curl error: network error', $request);
        }
        curl_close($this->ch);

        // Get the response
        return $response->getResponse();
    }

    /**
     * Create a new http response
     *
     * @param AbstractRequest $request
     *
     * @return ResponseBuilder
     *
     * @throws RuntimeException Failure to create stream
     */
    protected function createResponse($request)
    {
        try {
            $this->stream = new Stream();
            $content = fopen('php://temp', 'w+b');
            if ($content === false) {
                $body = $this->stream->create();
            } else {
                $body = $this->stream->create($content);
            }
        } catch (InvalidArgumentException $e) {
            throw new RuntimeException('Unable to create stream "php://temp"');
        }
        $responseObject = $request->getResponseObject();
        $message = DefaultResponse::getInstance($responseObject)
            ->withBody($body);

        return new ResponseBuilder(
            $message
        );
    }

    /**
     * Create array of headers to pass to CURLOPT_HTTPHEADER
     *
     * @param RequestInterface $request Request object
     * @param array<mixed> $options cURL options
     *
     * @return array<mixed> Array of http header lines
     */
    protected function createHeaders(RequestInterface $request, array $options)
    {
        $headers = [];
        $requestHeaders = $request->getHeaders();

        foreach ($requestHeaders as $name => $values) {
            $header = strtoupper($name);

            // cURL does not support 'Expect-Continue', skip all 'EXPECT' headers
            if ($header === 'EXPECT') {
                continue;
            }

            if ($header === 'CONTENT-LENGTH') {
                if (array_key_exists(CURLOPT_POSTFIELDS, $options)) {
                    $values = [strlen($options[CURLOPT_POSTFIELDS])];
                } // Force content length to '0' if body is empty
                elseif (!array_key_exists(CURLOPT_READFUNCTION, $options)) {
                    $values = [0];
                }
            }

            foreach ($values as $value) {
                $headers[] = $name . ': ' . $value;
            }
        }

        // Although cURL does not support 'Expect-Continue', it adds the 'Expect'
        // header by default, so we need to force 'Expect' to empty.
        $headers[] = 'Expect:';

        return $headers;
    }

    /**
     * Create cURL request options
     *
     * @param RequestInterface $request
     * @param ResponseBuilder $response
     *
     * @return array<mixed> cURL options
     *
     * @throws RequestException Invalid request
     * @throws InvalidArgumentException Invalid header names and/or values
     * @throws RuntimeException Unable to read request body
     */
    protected function createOptions(RequestInterface $request, ResponseBuilder $response)
    {
        $options = $this->options;

        // These options default to false and cannot be changed on set up.
        // The options should be provided with the request instead.
        $options[CURLOPT_FOLLOWLOCATION] = false;
        $options[CURLOPT_HEADER] = false;
        $options[CURLOPT_RETURNTRANSFER] = false;
        $options[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;

        try {
            $options[CURLOPT_HTTP_VERSION] = $this->getProtocolVersion($request->getProtocolVersion());
        } catch (UnexpectedValueException $e) {
            throw new RequestException($e->getMessage(), $request);
        }
        $options[CURLOPT_URL] = (string) $request->getUri();

        $options = $this->addRequestBodyOptions($request, $options);

        $options[CURLOPT_HTTPHEADER] = $this->createHeaders($request, $options);

        if ($request->getUri()->getUserInfo()) {
            $options[CURLOPT_USERPWD] = $request->getUri()->getUserInfo();
        }

        $options[CURLOPT_HEADERFUNCTION] = function ($ch, $data) use ($response) {
            $clean_data = trim($data);

            if ($clean_data !== '') {
                if (strpos(strtoupper($clean_data), 'HTTP/') === 0) {
                    $response->setStatus($clean_data)->getResponse();
                } else {
                    $response->addHeader($clean_data);
                }
            }

            return strlen($data);
        };

        $options[CURLOPT_WRITEFUNCTION] = function ($ch, $data) use ($response) {
            if (empty($response->getResponse()->getBody()) === false) {
                return $response->getResponse()->getBody()->write($data);
            }

            return 0;
        };

        return $options;
    }

    /**
     * Add cURL options related to the request body
     *
     * @param RequestInterface $request Request object
     * @param array<mixed> $options cURL options
     *
     * @return mixed
     */
    protected function addRequestBodyOptions(RequestInterface $request, array $options)
    {
        /*
         * HTTP methods that cannot have payload:
         * - GET   => cURL will automatically change method to PUT or POST if we
         *            set CURLOPT_UPLOAD or CURLOPT_POSTFIELDS.
         * - HEAD  => cURL treats HEAD as GET request with a same restrictions.
         * - TRACE => According to RFC7231: a client MUST NOT send a message body
         *            in a TRACE request.
         */
        $httpMethods = [
            'GET',
            'HEAD',
            'TRACE',
        ];
        if (!in_array($request->getMethod(), $httpMethods, true)) {
            $body = $request->getBody();
            $bodySize = $body->getSize();
            if ($bodySize !== 0) {
                if ($body->isSeekable()) {
                    $body->rewind();
                }
                if ($bodySize === null || $bodySize > self::$MAX_BODY_SIZE) {
                    $options[CURLOPT_UPLOAD] = true;

                    if ($bodySize !== null) {
                        $options[CURLOPT_INFILESIZE] = $bodySize;
                    }

                    $options[CURLOPT_READFUNCTION] = function ($ch, $fd, $len) use ($body) {
                        return $body->read($len);
                    };
                } else {
                    $options[CURLOPT_POSTFIELDS] = (string) $body;
                }
            }
        }

        if ($request->getMethod() === 'HEAD') {
            $options[CURLOPT_NOBODY] = true;
        } elseif ($request->getMethod() !== 'GET') {
            $options[CURLOPT_CUSTOMREQUEST] = $request->getMethod();
        }

        return $options;
    }

    /**
     * Get cURL constant for request http protocol version
     *
     * @param string $requestProtocolVersion Request http protocol version
     *
     * @return int cURL constant for request http protocol version
     *
     * @throws UnexpectedValueException Unsupported cURL http protocol version
     */
    protected function getProtocolVersion($requestProtocolVersion)
    {
        switch ($requestProtocolVersion) {
            case '1.0':
                return CURL_HTTP_VERSION_1_0;
            case '1.1':
                return CURL_HTTP_VERSION_1_1;
            case '2.0':
                if (defined('CURL_HTTP_VERSION_2_0')) {
                    return CURL_HTTP_VERSION_2_0;
                }

                throw new UnexpectedValueException('libcurl 7.33 required for HTTP 2.0');
        }

        return CURL_HTTP_VERSION_NONE;
    }
}
