<?php
error_reporting(E_ALL ^ E_USER_DEPRECATED ^ E_DEPRECATED);

chdir(dirname(dirname(__DIR__)));
require 'vendor/autoload.php';

/** @var \Laminas\ServiceManager\ServiceManager $container */
$container = require 'config/container.php';

\rollun\dic\InsideConstruct::setContainer($container);

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