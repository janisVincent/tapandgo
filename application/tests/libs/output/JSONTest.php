<?php

namespace Test;

use PHPUnit\Framework\TestCase;

/**
 * Class JSONTest
 * @package Test
 */
class JSONTest extends TestCase
{
    public function testRender()
    {
        $output = \Output\JSON::render([ 1 => 2 ]);
        $this->assertJson($output);
        $this->assertTrue(mb_detect_encoding($output, 'UTF-8') === 'UTF-8');
    }
}