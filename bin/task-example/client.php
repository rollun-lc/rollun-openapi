<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

\rollun\dic\InsideConstruct::setContainer($container);
$container->setService(\rollun\logger\LifeCycleToken::class, \rollun\logger\LifeCycleToken::generateToken());

/** @var \OpenAPI\Server\Rest\RestInterface $rest */
$rest = $container->get(\DataStoreExample\OpenAPI\V1\Client\Rest\User::class);

$bodyData = [
    [
        'id'   => '5f51f78ccaa4c122',
        'name' => 'John 22',
        'surname' => 'Smith 22',
        'active' => false,
    ]
];

$result = $rest->post($bodyData);
//$result = $rest->getById('5f51f78ccaa4c122');
//$result = $rest->deleteById('5f51f78ccaa4c122');

echo '<pre>';
print_r($result);
die();