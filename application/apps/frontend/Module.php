<?php

namespace Frontend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;

/**
 * Class Module
 * @package Frontend
 */
class Module implements ModuleDefinitionInterface
{
    /**
     * Register a specific autoloader for the module
     * @param DiInterface|null $di
     */
    public function registerAutoloaders(DiInterface $di = null)
    {
        $loader = new Loader();

        $loader->registerNamespaces(
            [
                "Frontend\\Controllers" => __DIR__ . "/controllers/",
                "Frontend\\Models" => __DIR__ . "/models/",
            ]
        );

        $loader->register();
    }

    /**
     * Register specific services for the module
     * @param DiInterface $di
     */
    public function registerServices(DiInterface $di)
    {
        // Registering a dispatcher
        $di->set(
            "dispatcher",
            function () {
                $dispatcher = new Dispatcher();
                $dispatcher->setDefaultNamespace("Frontend\\Controllers");

                return $dispatcher;
            }
        );

        // Registering the view component
        $di->set(
            "view",
            function () {
                $view = new View();
                $view->setViewsDir(__DIR__ . "/views/");

                return $view;
            }
        );
    }
}