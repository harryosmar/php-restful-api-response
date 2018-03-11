<?php
/**
 * Created by PhpStorm.
 * User: harry
 * Date: 2/14/18
 * Time: 11:57 AM
 */

namespace PhpRestfulApiResponse\Contracts;

use League\Fractal\Pagination\Cursor;
use League\Fractal\TransformerAbstract;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\Response;

interface PhpRestfulApiResponse extends ResponseInterface
{
    /**
     * @param array|null $data
     * @param $code
     * @param array $headers
     * @return Response|static
     */
    public function withArray($data, $code = 200, array $headers = []);

    /**
     * @param $data
     * @param TransformerAbstract|callable $transformer
     * @param int $code
     * @param null $resourceKey
     * @param array $meta
     * @param array $headers
     * @return Response
     */
    public function withItem($data, $transformer, $code = 200, $resourceKey = null, $meta = [], array $headers = []);

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
    public function withCollection($data, $transformer, $code = 200, $resourceKey = null, Cursor $cursor = null, $meta = [], array $headers = []);

    /**
     * Generates a response with custom code HTTP header and a given message.
     *
     * @param $message
     * @param int $statusCode
     * @param int|string $errorCode
     * @param array $headers
     * @return mixed
     */
    public function withError($message, int $statusCode, $errorCode = null, array $headers = []);

    /**
     * Generates a response with a 403 HTTP header and a given message.
     *
     * @param string $message
     * @param int|string $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorForbidden(string $message = '', $errorCode = null, array $headers = []);

    /**
     * Generates a response with a 500 HTTP header and a given message.
     *
     * @param string $message
     * @param int|string $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorInternalError(string $message = '', $errorCode = null, array $headers = []);

    /**
     * Generates a response with a 404 HTTP header and a given message.
     *
     * @param string $message
     * @param int|string $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorNotFound(string $message = '', $errorCode = null, array $headers = []);

    /**
     * Generates a response with a 401 HTTP header and a given message.
     *
     * @param string $message
     * @param int|string $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorUnauthorized(string $message = '', $errorCode = null, array $headers = []);

    /**
     * Generates a response with a 400 HTTP header and a given message.
     *
     * @param array $message
     * @param int|array $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorWrongArgs(array $message, $errorCode = null, array $headers = []);

    /**
     * Generates a response with a 410 HTTP header and a given message.
     *
     * @param string $message
     * @param int|string $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorGone(string $message = '', $errorCode = null, array $headers = []);

    /**
     * Generates a response with a 405 HTTP header and a given message.
     *
     * @param string $message
     * @param int|string $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorMethodNotAllowed(string $message = '', $errorCode = null, array $headers = []);

    /**
     * Generates a Response with a 431 HTTP header and a given message.
     *
     * @param string $message
     * @param int|string $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorUnwillingToProcess(string $message = '', $errorCode = null, array $headers = []);

    /**
     * Generates a Response with a 422 HTTP header and a given message.
     *
     * @param string $message
     * @param int|string $errorCode
     * @param array  $headers
     * @return mixed
     */
    public function errorUnprocessable(string $message = '', $errorCode = null, array $headers = []);
}