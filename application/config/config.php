<?php

return new \Phalcon\Config([
    "database" => [
        "host" => "mysql",
        "username" => "beapp_tapandgo",
        "password" => "WbU7QxMdwCfW",
        "dbname" => "beapp_tapandgo",
        "options" => array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
        ),
    ],
    "auth" => [
        "basic" => [
            "username" => "username",
            "password" => "password",
        ]
    ],
]);
