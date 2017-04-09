<?php

use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Db\Adapter\Pdo\Mysql as PdoMysql;
use Phalcon\Mvc\Application;

$loader = new Loader();

$loader->registerDirs([
    __DIR__ . "/../libs/",
]);

$loader->register();

$di = new FactoryDefault();
$config = require __DIR__ . "/../config/config.php";

// Authentification
$auth = new \Auth\Basic("tapandgo");
if (false === $auth->validate($config)) {
    exit;
}

// Share config to applications
$di->setShared("config", $config);

// Set up the database service
$di->setShared("db", function () use ($config) {
    $dbConfig = $config->database->toArray();
    return new PdoMysql($dbConfig);
});

// Specify routes for modules
$di->set(
    "router",
    function () {
        return require __DIR__ . "/routes.php";
    }
);

// Create an application
$application = new Application($di);

// Register the installed modules
$application->registerModules([
    "frontend" => [
        "className" => "Frontend\\Module",
        "path" => __DIR__ . "/../apps/frontend/Module.php",
    ],
    "backend" => [
        "className" => "Backend\\Module",
        "path" => __DIR__ . "/../apps/backend/Module.php",
    ]
]);

try {
    // Handle the request
    $response = $application->handle();
    $response->send();

} catch (\Exception $exception) {
    // @todo Log exception
}