<?php
/**
 * Created by PhpStorm.
 * User: harry
 * Date: 2/12/18
 * Time: 4:06 PM
 */

namespace PhpRestfulApiResponse\Tests\unit;

use PHPUnit\Framework\TestCase;

class Base extends TestCase
{
    protected function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
    }

    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown(); // TODO: Change the autogenerated stub
    }
}