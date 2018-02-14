<?php
/**
 * Created by PhpStorm.
 * User: harry
 * Date: 2/14/18
 * Time: 1:32 PM
 */

namespace PhpRestfulApiResponse\Tests\unit\Lib\Transformer;

use League\Fractal\TransformerAbstract;
use PhpRestfulApiResponse\Tests\unit\Lib\Book as BookObject;

class Book extends TransformerAbstract
{
    public function transform(BookObject $book)
    {
        return [
            'title' =>$book->getTitle(),
            'author' => [
                'name' => $book->getAuthorName(),
                'email' => $book->getAuthorEmail()
            ],
            'year' => $book->getYear(),
            'price' => $book->getPrice(),
        ];
    }
}