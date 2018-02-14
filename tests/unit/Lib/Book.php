<?php
/**
 * Created by PhpStorm.
 * User: harry
 * Date: 2/14/18
 * Time: 1:15 PM
 */

namespace PhpRestfulApiResponse\Tests\unit\Lib;


class Book
{
    private $authorName;

    private $authorEmail;

    private $title;

    private $price;

    private $year;

    public function __construct($authorName, $authorEmail, $title, $price, $year)
    {
        $this->authorName = $authorName;
        $this->authorEmail = $authorEmail;
        $this->title = $title;
        $this->price = $price;
        $this->year = $year;
    }

    /**
     * @return mixed
     */
    public function getAuthorName()
    {
        return $this->authorName;
    }

    /**
     * @return mixed
     */
    public function getAuthorEmail()
    {
        return $this->authorEmail;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }
}