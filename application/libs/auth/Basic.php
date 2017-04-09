<?php

namespace Auth;

/**
 * Class Basic
 * @package Auth
 */
class Basic implements \Auth
{
    private $realm;

    /**
     * Basic constructor.
     * @param string $realm
     */
    public function __construct(string $realm)
    {
        $this->realm = $realm;
    }

    /**
     * @param \Phalcon\Config $config
     * @return bool
     */
    public function validate(\Phalcon\Config $config)
    {
        if (empty($_SERVER['PHP_AUTH_USER']) || ($_SERVER['PHP_AUTH_USER'] != $config->auth->basic->username)
            || empty($_SERVER['PHP_AUTH_PW']) || ($_SERVER['PHP_AUTH_PW'] != $config->auth->basic->password)
        ) {
            http_response_code(401);
            if (!headers_sent()) {
                header('WWW-Authenticate: Basic realm="' . $this->realm . '"');
            }

            return false;
        }

        return true;
    }
}