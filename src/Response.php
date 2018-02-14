<?php
/**
 * Created by PhpStorm.
 * User: harry
 * Date: 2/14/18
 * Time: 11:58 AM
 */

namespace PhpRestfulApiResponse;

use League\Fractal\Manager;
use League\Fractal\Pagination\Cursor;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use League\Fractal\TransformerAbstract;
use PhpRestfulApiResponse\Contracts\PhpRestfulApiResponse;
use Zend\Diactoros\MessageTrait;
use InvalidArgumentException;

class Response implements PhpRestfulApiResponse
{
    use MessageTrait;

    const MIN_STATUS_CODE_VALUE = 100;
    const MAX_STATUS_CODE_VALUE = 599;

    /**
     * Map of standard HTTP status code/reason phrases
     *
     * @var array
     */
    private $phrases = [
        // INFORMATIONAL CODES
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        103 => 'Early Hints',
        // SUCCESS CODES
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',
        208 => 'Already Reported',
        226 => 'IM Used',
        // REDIRECTION CODES
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy', // Deprecated to 306 => '(Unused)'
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        // CLIENT ERROR
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Payload Too Large',
        414 => 'URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Range Not Satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        421 => 'Misdirected Request',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        444 => 'Connection Closed Without Response',
        451 => 'Unavailable For Legal Reasons',
        // SERVER ERROR
        499 => 'Client Closed Request',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
        599 => 'Network Connect Timeout Error',
    ];

    /**
     * @var string
     */
    private $reasonPhrase = '';

    /**
     * @var int
     */
    private $statusCode;

    public function __construct($body = 'php://memory', $status = 200, array $headers = [])
    {
        $this->setStatusCode($status);
        $this->stream = $this->getStream($body, 'wb+');
        $this->setHeaders($headers);
    }

    /**
     * {@inheritdoc}
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * {@inheritdoc}
     */
    public function getReasonPhrase()
    {
        if (! $this->reasonPhrase
            && isset($this->phrases[$this->statusCode])
        ) {
            $this->reasonPhrase = $this->phrases[$this->statusCode];
        }

        return $this->reasonPhrase;
    }

    /**
     * {@inheritdoc}
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $new = clone $this;
        $new->setStatusCode($code);
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

    /**
     * @param $data
     * @param $code
     * @param array $headers
     * @return Response|static
     */
    public function withArray(array $data, $code = 200, array $headers = [])
    {
        $new = clone $this;
        $new->setStatusCode($code);
        $new->getBody()->write(json_encode($data));
        $new = $new->withHeader('Content-Type', 'application/json');
        $new->headers = array_merge($new->headers, $headers);
        return $new;
    }

    /**
     * @param $data
     * @param TransformerAbstract|callable $transformer
     * @param int $code
     * @param null $resourceKey
     * @param array $meta
     * @param array $headers
     * @return Response
     */
    public function withItem($data, $transformer, $code = 200, $resourceKey = null, $meta = [], array $headers = [])
    {
        $resource = new Item($data, $transformer, $resourceKey);

        foreach ($meta as $metaKey => $metaValue) {
            $resource->setMetaValue($metaKey, $metaValue);
        }

        $manager = new Manager();

        $rootScope = $manager->createData($resource);

        return $this->withArray($rootScope->toArray(), $code, $headers);
    }

    /**
     * @param $data
     * @param TransformerAbstract|callable $transformer
     * @param int $code
     * @param null $resourceKey
     * @param Cursor|null $cursor
     * @param array $meta
     * @param array $headers
     * @return Response
     */
    public function withCollection($data, $transformer, $code = 200, $resourceKey = null, Cursor $cursor = null, $meta = [], array $headers = [])
    {
        $resource = new Collection($data, $transformer, $resourceKey);

        foreach ($meta as $metaKey => $metaValue) {
            $resource->setMetaValue($metaKey, $metaValue);
        }

        if (!is_null($cursor)) {
            $resource->setCursor($cursor);
        }

        $manager = new Manager();

        $rootScope = $manager->createData($resource);

        return $this->withArray($rootScope->toArray(), $code, $headers);
    }

    /**
     * Response for errors
     *
     * @param string|array $message
     * @param string $code
     * @param array  $headers
     * @return mixed
     */
    public function withError($message, $code, array $headers = [])
    {
        $new = clone $this;
        $new->setStatusCode($code);
        $new->getBody()->write(
            json_encode(
                [
                    'error' => array_filter([
                        'http_code' => $new->statusCode,
                        'phrase' => $new->getReasonPhrase(),
                        'message' => $message
                    ])
                ]
            )
        );
        $new = $new->withHeader('Content-Type', 'application/json');
        $new->headers = array_merge($new->headers, $headers);
        return $new;
    }

    /**
     * Generates a response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @param array  $headers
     * @return mixed
     */
    public function errorForbidden($message = '', array $headers = [])
    {
        return $this->withError($message, 403, $headers);
    }

    /**
     * Generates a response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @param array  $headers
     * @return mixed
     */
    public function errorInternalError($message = '', array $headers = [])
    {
        return $this->withError($message, 500, $headers);
    }

    /**
     * Generates a response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @param array  $headers
     * @return mixed
     */
    public function errorNotFound($message = '', array $headers = [])
    {
        return $this->withError($message, 404, $headers);
    }

    /**
     * Generates a response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return mixed
     */
    public function errorUnauthorized($message = '', array $headers = [])
    {
        return $this->withError($message, 401, $headers);
    }

    /**
     * Generates a response with a 400 HTTP header and a given message.
     *
     * @param array $message
     * @param array $headers
     * @return mixed
     */
    public function errorWrongArgs(array $message, array $headers = [])
    {
        return $this->withError($message, 400, $headers);
    }

    /**
     * Generates a response with a 410 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return mixed
     */
    public function errorGone($message = '', array $headers = [])
    {
        return $this->withError($message, 410, $headers);
    }

    /**
     * Generates a response with a 405 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return mixed
     */
    public function errorMethodNotAllowed($message = '', array $headers = [])
    {
        return $this->withError($message, 405, $headers);
    }

    /**
     * Generates a Response with a 431 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return mixed
     */
    public function errorUnwillingToProcess($message = '', array $headers = [])
    {
        return $this->withError($message, 431, $headers);
    }

    /**
     * Generates a Response with a 422 HTTP header and a given message.
     *
     * @param string $message
     * @param array $headers
     * @return mixed
     */
    public function errorUnprocessable($message = '', array $headers = [])
    {
        return $this->withError($message, 422, $headers);
    }

    /**
     * Set a valid status code.
     *
     * @param int $code
     * @throws InvalidArgumentException on an invalid status code.
     */
    private function setStatusCode($code)
    {
        if (! is_numeric($code)
            || is_float($code)
            || $code < static::MIN_STATUS_CODE_VALUE
            || $code > static::MAX_STATUS_CODE_VALUE
        ) {
            throw new InvalidArgumentException(sprintf(
                'Invalid status code "%s"; must be an integer between %d and %d, inclusive',
                (is_scalar($code) ? $code : gettype($code)),
                static::MIN_STATUS_CODE_VALUE,
                static::MAX_STATUS_CODE_VALUE
            ));
        }
        $this->statusCode = $code;
    }
}