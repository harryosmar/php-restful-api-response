# php restful api response implement PSR-7: HTTP message interfaces

## Requirements
- php >= 5.6
- composer https://getcomposer.org/download/

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
        "harryosmar/php-restful-api-response": "^1.0"
    }
}
```
- *then run `composer install` or `composer update`*

## How To Use
- First instantiate the response object
```
<?php

use PhpRestfulApiResponse\Response;

$response = new Response();
```
- With simple array
```
<?php
echo $response->withArray([
    'status' => 'created',
    'id' => 1
], 201); //response code 201
```
```json
{
    "status": "created",
    "id": 1
}
```
- With item/object

For this sample, we use [class Book](https://github.com/harryosmar/php-restful-api-response/blob/master/tests/unit/Lib/Book.php) as an item

```
<?php
use PhpRestfulApiResponse\Tests\unit\Lib\Book;

echo $response->withItem(
    new Book('harry', 'harryosmarsitohang@gmail.com', 'how to be a ninja', 100000, 2017),
    new \PhpRestfulApiResponse\Tests\unit\Lib\Transformer\Book,
    200 //response code 200
);
```
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
- With collection of items
```
<?php
use PhpRestfulApiResponse\Tests\unit\Lib\Book;

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
- 404 Not Found
```
<?php
echo $response->errorNotFound();
```
```json
{
    "error":
    {
        "http_code": 404,
        "phrase": "Not Found"
    }
}
```
- 500 Internal Server Error
```
<?php
echo $response->errorInternalError();
```
```json
{
    "error":
    {
        "http_code": 500,
        "phrase": "Internal Server Error"
    }
}
```
- 400 Bad Request
```
<?php
echo $response->errorWrongArgs([
   'username' => 'required',
   'password' => 'required'
]);
```
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
- 401 Unauthorized
```
<?php
echo $response->errorUnauthorized();
```
```json
{
    "error":
    {
        "http_code": 401,
        "phrase": "Unauthorized"
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
