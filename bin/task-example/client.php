<?php
use rollun\dic\InsideConstruct;
use rollun\logger\LifeCycleToken;
use Task\OpenAPI\Client\V1\Api\FileSummaryApi;

error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Interop\Container\ContainerInterface $container */
$container = require 'config/container.php';
InsideConstruct::setContainer($container);
$lifeCycleToken = LifeCycleToken::generateToken();
$container->setService(LifeCycleToken::class, $lifeCycleToken);

/** @var FileSummaryApi $client */
$client = $container->get(FileSummaryApi::class);

// prepare inline object
$inlineObject = new \Task\OpenAPI\Client\V1\Model\InlineObject(['n' => 5]);

$result = $client->fileSummaryPost($inlineObject);
//$result = $client->fileSummaryIdGet($inlineObject->getN());
//$result = $client->fileSummaryIdDelete($inlineObject->getN());

echo '<pre>';
print_r($result);
die();