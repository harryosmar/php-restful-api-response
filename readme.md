# php rest-api response implement PSR-7: HTTP message interfaces

[![Latest Version](https://img.shields.io/github/release/harryosmar/php-restful-api-response.svg?style=flat-square)](https://github.com/harryosmar/php-restful-api-response/releases)
[![Build Status](https://travis-ci.org/harryosmar/php-restful-api-response.svg?branch=master)](https://travis-ci.org/harryosmar/php-restful-api-response)
[![Build Status](https://scrutinizer-ci.com/g/harryosmar/php-restful-api-response/badges/build.png?b=master)](https://scrutinizer-ci.com/g/harryosmar/php-restful-api-response/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/harryosmar/php-restful-api-response/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/harryosmar/php-restful-api-response/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/harryosmar/php-restful-api-response/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/harryosmar/php-restful-api-response/?branch=master)

## Requirements
- php >= 7.0
- composer https://getcomposer.org/download/

## Features
- Implement PSR-7: HTTP message interfaces, extend https://github.com/zendframework/zend-diactoros
- Provides response format [collection](#with-collection) & [item](#with-item) using library http://fractal.thephpleague.com/
- Provides basic [errors response](#error)


## How To Setup
- *add this lines to your `composer.json` file*
```
{
    "repositories": [
        {
            "type": "vcs",
            "url": "git@github.com:harryosmar/php-restful-api-response.git"
        }
    ],
    "require": {
        "harryosmar/php-restful-api-response": "^1.1"
    }
}
```
- *then run `composer install` or `composer update`*

## How To Use

Simple example how to use
```
<?php

use PhpRestfulApiResponse\Response;

$response = new Response();

echo $response->withArray([
    'status' => 'created',
    'id' => 1
], 200); //response code 200
```
response
```json
{
    "status": "created",
    "id": 1
}
```

## Available Response Format
* [with array](#with-array)
* [with item](#with-item)
* [with collection](#with-collection)
* [error](#error)
    * [With Error](#with-error)
    * [403 Forbidden](#403-forbidden)
    * [500 Internal Server Error](#500-internal-server-error)
    * [404 Not Found](#404-not-found)
    * [401 Unauthorized](#401-unauthorized)
    * [400 Bad Request](#400-bad-request)
    * [410 Gone](#410-gone)
    * [405 Method Not Allowed](#405-method-not-allowed)
    * [431 Request Header Fields Too Large](#431-request-header-fields-too-large)
    * [422 Unprocessable Entity](#422-unprocessable-entity)

##### With Array
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->withArray([
    'status' => 'created',
    'id' => 1
], 201); //response code 201
```
response
```json
{
    "status": "created",
    "id": 1
}
```

##### With Item
For this sample, we use [class Book](https://github.com/harryosmar/php-restful-api-response/blob/master/tests/unit/Lib/Book.php) as an item
```php
<?php
use PhpRestfulApiResponse\Tests\unit\Lib\Book;

/** @var \PhpRestfulApiResponse\Response $response */
echo $response->withItem(
    new Book('harry', 'harryosmarsitohang@gmail.com', 'how to be a ninja', 100000, 2017),
    new \PhpRestfulApiResponse\Tests\unit\Lib\Transformer\Book,
    201
);
```
response 201
```json
{
    "data":
    {
        "title": "how to be a ninja",
        "author":
        {
            "name": "harry",
            "email": "harryosmarsitohang@gmail.com"
        },
        "year": 2017,
        "price": 100000
    }
}
```

##### With Collection
```php
<?php
use PhpRestfulApiResponse\Tests\unit\Lib\Book;

/** @var \PhpRestfulApiResponse\Response $response */
$response->withCollection(
    [
        new Book('harry', 'harryosmarsitohang@gmail.com', 'how to be a ninja', 100000, 2017),
        new Book('harry', 'harryosmarsitohang@gmail.com', 'how to be a mage', 500000, 2016),
        new Book('harry', 'harryosmarsitohang@gmail.com', 'how to be a samurai', 25000, 2000),
    ],
    new \PhpRestfulApiResponse\Tests\unit\Lib\Transformer\Book,
    200
);
```
response 200
```json
{
    "data": [
    {
        "title": "how to be a ninja",
        "author":
        {
            "name": "harry",
            "email": "harryosmarsitohang@gmail.com"
        },
        "year": 2017,
        "price": 100000
    },
    {
        "title": "how to be a mage",
        "author":
        {
            "name": "harry",
            "email": "harryosmarsitohang@gmail.com"
        },
        "year": 2016,
        "price": 500000
    },
    {
        "title": "how to be a samurai",
        "author":
        {
            "name": "harry",
            "email": "harryosmarsitohang@gmail.com"
        },
        "year": 2000,
        "price": 25000
    }]
}
```

#### Error

##### With Error
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->withError(['error' => 'something is wrong, please try again'], 500);
```
response 500
```json
{
    "error": "something is wrong, please try again"
}
```

##### 403 Forbidden
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorNotFound();
```
response 403
```json
{
    "error":
    {
        "http_code": 403,
        "phrase": "Forbidden"
    }
}
```

##### 500 Internal Server Error
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorInternalError();
```
response 500
```json
{
    "error":
    {
        "http_code": 500,
        "phrase": "Internal Server Error"
    }
}
```

##### 404 Not Found
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorNotFound();
```
response 404
```json
{
    "error":
    {
        "http_code": 404,
        "phrase": "Not Found"
    }
}
```

##### 401 Unauthorized
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorUnauthorized();
```
response 401
```json
{
    "error":
    {
        "http_code": 401,
        "phrase": "Unauthorized"
    }
}
```

##### 400 Bad Request
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorWrongArgs([
   'username' => 'required',
   'password' => 'required'
]);
```
response 400
```json
{
    "error":
    {
        "http_code": 400,
        "phrase": "Bad Request",
        "message":
        {
            "username": "required",
            "password": "required"
        }
    }
}
```

##### 410 Gone
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorGone();
```
response 410
```json
{
    "error":
    {
        "http_code": 410,
        "phrase": "Gone"
    }
}
```

##### 405 Method Not Allowed
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorMethodNotAllowed();
```
response 405
```json
{
    "error":
    {
        "http_code": 405,
        "phrase": "Method Not Allowed"
    }
}
```

##### 431 Request Header Fields Too Large
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorUnwillingToProcess();
```
response 431
```json
{
    "error":
    {
        "http_code": 431,
        "phrase": "Request Header Fields Too Large"
    }
}
```

##### 422 Unprocessable Entity
```php
<?php
/** @var \PhpRestfulApiResponse\Response $response */
echo $response->errorUnprocessable();
```
response 422
```json
{
    "error":
    {
        "http_code": 422,
        "phrase": "Unprocessable Entity"
    }
}
```

## How To Run The Test
```
composer test
```

## How To Contribute
- Fork this repo
- post an issue https://github.com/harryosmar/php-restful-api-response/issues
- create the PR(Pull Request) and wait for the review
