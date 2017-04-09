<?php

namespace Backend;

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\DiInterface;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Session\Adapter\Redis as Session;
use Phalcon\Flash\Session as FlashSession;

/**
 * Class Module
 * @package Backend
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
                "Backend\\Controllers" => __DIR__ . "/controllers/",
                "Backend\\Models" => __DIR__ . "/models/",
                "Backend\\Forms" => __DIR__ . "/forms/",
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
                $dispatcher->setDefaultNamespace("Backend\\Controllers");

                return $dispatcher;
            }
        );

        // Register Volt as a service
        $di->set(
            "voltService",
            function ($view, $di) {
                $volt = new Volt($view, $di);

                $volt->setOptions(
                    [
                        "compiledPath" => __DIR__ . "/compile/",
                        "compiledExtension" => ".compiled",
                    ]
                );

                return $volt;
            }
        );

        // Register Volt as template engine
        $di->set(
            "view",
            function () {
                $view = new View();

                $view->setViewsDir(__DIR__ . "/views/");
                $view->registerEngines(
                    [
                        ".phtml" => "voltService",
                    ]
                );

                return $view;
            }
        );

        $di->setShared(
            "session",
            function () {
                $session = new Session(
                    [
                        "host" => "redis",
                    ]
                );
                $session->start();

                return $session;
            }
        );

        // Set up the flash session service
        $di->set(
            "flashSession",
            function () {
                $flash = new FlashSession(
                    [
                        // bootstrap classes
                        'error' => 'alert alert-danger',
                        'success' => 'alert alert-success',
                        'notice' => 'alert alert-info',
                        'warning' => 'alert alert-warning'
                    ]
                );

                return $flash;
            }
        );
    }
}