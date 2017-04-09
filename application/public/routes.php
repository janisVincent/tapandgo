<?php

$router = new Phalcon\Mvc\Router(false);

/**
 * API
 */
$router->addGet(
    "/api/:controller",
    [
        "module" => "frontend",
        "controller" => 1,
        "action" => "index",
    ]
);

$router->addGet(
    "/api/:controller/([0-9]+)",
    [
        "module" => "frontend",
        "controller" => 1,
        "action" => "show",
        "id" => 2,
    ]
);

$router->addGet(
    "/api/stations/near",
    [
        "module" => "frontend",
        "controller" => "stations",
        "action" => "near",
    ]
);

$router->addPost(
    "/api/stations/([0-9]+)/:action",
    [
        "module" => "frontend",
        "controller" => "stations",
        "id" => 1,
        "action" => 2,
    ]
);

/**
 * Admin
 */
$router->addGet(
    "/admin",
    [
        "module" => "backend",
        "controller" => "index",
        "action" => "index",
    ]
);

$router->add(
    "/admin/:controller",
    [
        "module" => "backend",
        "controller" => 1,
        "action" => "index",
    ]
)->setName("admin_controller");

$router->addGet(
    "/admin/:controller/:action",
    [
        "module" => "backend",
        "controller" => 1,
        "action" => 2,
    ]
);

$router->addPost(
    "/admin/:controller/create",
    [
        "module" => "backend",
        "controller" => 1,
        "action" => "create",
    ]
);

$router->addPost(
    "/admin/:controller/save/:params",
    [
        "module" => "backend",
        "controller" => 1,
        "action" => "save",
        "params" => 2,
    ]
);

$router->addGet(
    "/admin/:controller/:action/:params",
    [
        "module" => "backend",
        "controller" => 1,
        "action" => 2,
        "params" => 3,
    ]
);

$router->notFound(array(
    "module" => "frontend",
    "controller" => "errors",
    "action" => "notFound404"
));

return $router;