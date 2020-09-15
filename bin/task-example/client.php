<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

\rollun\dic\InsideConstruct::setContainer($container);
$container->setService(\rollun\logger\LifeCycleToken::class, \rollun\logger\LifeCycleToken::generateToken());

/** @var \OpenAPI\Server\Rest\RestInterface $rest */
$rest = $container->get(\Task\OpenAPI\V1\Client\Rest\FileSummary::class);

$bodyData = [
    'n' => 13
];

$result = $rest->post($bodyData);
//$result = $rest->getById(12);
//$result = $rest->deleteById(12);

echo '<pre>';
print_r($result);
die();