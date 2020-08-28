<?php

echo "Please set path to openapi manifest (openapi.yaml by default): ";
$manifest = trim(fgets(fopen('php://stdin', 'rw+')));
if (empty($manifest)) {
    $manifest = 'openapi.yaml';
}

if (!file_exists($manifest)) {
    echo "Openapi manifest file doesn't exists!" . PHP_EOL;
    exit(1);
}

// parse manifest
if (strpos($manifest, '.yaml') !== false) {
    $manifestData = yaml_parse(file_get_contents($manifest));
} elseif (strpos($manifest, '.json') !== false) {
    $manifestData = json_decode(file_get_contents($manifest), true);
} else {
    echo 'Unknown openapi manifest file format! Only yaml or json allowed.' . PHP_EOL;
    exit(1);
}

if (!isset($manifestData['info']['title'])) {
    echo 'Title is required for openapi manifest.' . PHP_EOL;
    exit(1);
}

// prepare title
$title = preg_replace("/[^a-zA-Z0-9]/", '', $manifestData['info']['title']);

// prepare version
$version = preg_replace("/[^0-9]/", '', $manifestData['info']['version']);

// create generator config
file_put_contents('openapi_client_config.json', json_encode(['invokerPackage' => "$title\\OpenAPI\\Client\\V$version", 'srcBasePath' => "src/$title/src/OpenAPI/Client/V$version"]));
sleep(1);

// prepare template path
$templatePath = dirname(__DIR__) . '/template';

// generate
exec(
    "openapi-generator generate --global-property apis,apiDocs=false,apiTests=false,models,modelDocs=false,modelTests=false,supportingFiles=Configuration.php -i $manifest -o tmp -g php -c openapi_client_config.json -t $templatePath",
    $output
);

// copy
exec("cp -R tmp/src/. src/", $output1);

// clearing
exec("rm -R tmp", $output2);
exec("rm openapi_client_config.json", $output3);

// show generator messages
foreach (array_merge($output, $output1, $output2, $output3) as $v) {
    echo $v . PHP_EOL;
}
die();