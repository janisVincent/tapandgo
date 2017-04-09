<?php

namespace Test;

use PHPUnit\Framework\TestCase;

/**
 * Class BasicTest
 * @package Test
 */
class BasicTest extends TestCase
{
    public function testValidate()
    {
        $auth = new \Auth\Basic("test");

        $config = require ROOT_PATH . "/../config/config.php";
        $_SERVER['PHP_AUTH_USER'] = $config->auth->basic->username;
        $_SERVER['PHP_AUTH_PW'] = $config->auth->basic->password;

        $return = $auth->validate($config);

        // $this->assertFalse(http_response_code());
        $this->assertNotEquals(401, http_response_code());
        $this->assertTrue($return);
    }

    public function testValidateError()
    {
        $auth = new \Auth\Basic("test");

        $config = new \Phalcon\Config([
            "auth" => [
                "basic" => [
                    "username" => "test",
                    "password" => "test",
                ],
            ],
        ]);

        $return = $auth->validate($config);

        $this->assertEquals(401, http_response_code());
        $this->assertFalse($return);
    }
}