<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';

\rollun\dic\InsideConstruct::setContainer($container);

$client = $container->get(\HelloUser\OpenAPI\Client\V1\Api\UserApi::class);

// prepare inline object
//$inlineObject = new \HelloUser\OpenAPI\Client\V1\Model\InlineObject(['id1' => 'q11', 'name1' => 'Test 1']);

//$result = $client->userPost($inlineObject);
$result = $client->userIdGet(22);
//$result = $client->fileSummaryIdDelete($inlineObject->getN());

echo '<pre>';
print_r($result);
die();