<?php
/**
 * @copyright Copyright © 2014 Rollun LC (http://rollun.com/)
 * @license LICENSE.md New BSD License
 */

declare(strict_types=1);

use rollun\dic\InsideConstruct;
use rollun\logger\LifeCycleToken;
use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Laminas\ServiceManager\ServiceManager;

error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(__DIR__));
require 'vendor/autoload.php';

/**
 * Self-called anonymous function that creates its own scope and keep the global namespace clean.
 */
(function () {
    /** @var ServiceManager $container */
    $container = require 'config/container.php';

    InsideConstruct::setContainer($container);

    // Init lifecycle token
    $lifeCycleToken = LifeCycleToken::generateToken();
    if (LifeCycleToken::getAllHeaders() && array_key_exists("LifeCycleToken", LifeCycleToken::getAllHeaders())) {
        $lifeCycleToken->unserialize(LifeCycleToken::getAllHeaders()["LifeCycleToken"]);
    }
    $container->setService(LifeCycleToken::class, $lifeCycleToken);

    /** @var Application $app */
    $app = $container->get(Application::class);
    $factory = $container->get(MiddlewareFactory::class);

    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
    (require 'config/pipeline.php')($app, $factory, $container);
    (require 'config/routes.php')($app, $factory, $container);

    $app->run();
})();
