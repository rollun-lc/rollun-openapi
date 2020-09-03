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

// prepare template path
$templatePath = dirname(__DIR__) . '/template/server';

// create generator config
file_put_contents('openapi_config.json', json_encode(['invokerPackage' => "$title\\OpenAPI\\Server\\V$version", 'srcBasePath' => "src/$title/src/OpenAPI/Server/V$version"]));

// generate
exec("openapi-generator generate -i $manifest -o tmp-openapi -g php-ze-ph -c openapi_config.json -t $templatePath", $output);

// generate config to autoload
$pathHandlerData = yaml_parse(file_get_contents('tmp-openapi/application/config/path_handler.yml'));

$file = 'config/autoload/' . lcfirst($title) . '_v' . $version . '_path_handler.global.php';
$content = "<?php\n\nreturn [\n\Articus\PathHandler\RouteInjection\Factory::class => [\n'paths'=>[\n";
foreach ($pathHandlerData['Articus\PathHandler\RouteInjection\Factory']['paths'] as $path => $handlers) {
    $content .= "'$path' => [\n";
    foreach ($handlers as $handler) {
        $content .= "\\$handler::class,\n";
    }
    $content .= "],\n";
}
$content .= "],\n";
$content .= "]\n];";
file_put_contents($file, $content);

// copy
exec("cp -R tmp-openapi/src/$title/. src/$title/", $output1);

// clearing
exec("rm -R tmp-openapi", $output2);
exec("rm openapi_config.json", $output3);

// create api docs
$docsDir = "public/openapi/docs/$title/v$version";
if (!file_exists($docsDir)) {
    mkdir($docsDir, 0777, true);
}

// create docs index.html
$html = file_get_contents('template/server/docs/index.html');
$html = str_replace('{{title}}', $manifestData['info']['title'], $html);
$manifestParts = explode("/", $manifest);
$html = str_replace('{{manifest}}', array_pop($manifestParts), $html);

file_put_contents($docsDir . '/index.html', $html);

// copy
exec("cp $manifest $docsDir/", $output4);

// show generator messages
foreach (array_merge($output, $output1, $output2, $output3, $output4) as $v) {
    echo $v . PHP_EOL;
}
die();