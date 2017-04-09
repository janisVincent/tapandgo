<?php

namespace Test;

use Phalcon\Test\ModelTestCase;

/**
 * Class CitiesTest
 * @package Test
 */
class CitiesTest extends ModelTestCase
{
    public function testGetId()
    {
        $city = new \Frontend\Models\Cities();
        $city->id = "1";
        $this->assertInternalType("string", $city->id);
        $this->assertInternalType("integer", $city->getId());
    }

    public function testGetName()
    {
        $city = new \Frontend\Models\Cities();
        $city->name = " Nantes ";
        $this->assertEquals(" ", mb_substr($city->name, 0, 1));
        $this->assertEquals("Nantes", $city->getName());
    }
}