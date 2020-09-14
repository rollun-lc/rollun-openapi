<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

\rollun\dic\InsideConstruct::setContainer($container);
$container->setService(\rollun\logger\LifeCycleToken::class, \rollun\logger\LifeCycleToken::generateToken());

/** @var \OpenAPI\Server\Rest\RestInterface $rest */
$rest = $container->get(\HelloUser\OpenAPI\V1\Client\Rest\User::class);

$id = 'a1231';

//$result = $rest->post(['id' => $id, 'name' => 'Test 123']);
$result = $rest->getById($id);
//$result = $rest->deleteById($id);

echo '<pre>';
print_r($result);
die();