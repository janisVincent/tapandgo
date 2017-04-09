<?php

/**
 * Interface Auth
 */
interface Auth
{
    /**
     * Auth constructor.
     * @param string $realm
     */
    public function __construct(string $realm);

    /**
     * @param \Phalcon\Config $config
     */
    public function validate(\Phalcon\Config $config);
}