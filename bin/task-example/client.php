<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

\rollun\dic\InsideConstruct::setContainer($container);

/** @var \OpenAPI\Server\Rest\RestInterface $rest */
$rest = $container->get(\HelloUser\OpenAPI\Client\V1\Rest\User::class);

//$result = $rest->post(['id' => 'q1', 'name' => 'Test 123']);
$result = $rest->getById('q1');
//$result = $rest->deleteById(22);

echo '<pre>';
print_r($result);
die();