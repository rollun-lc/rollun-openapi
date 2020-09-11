<?php

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    chdir(dirname(__DIR__));
} elseif (file_exists(dirname(dirname(__DIR__)) . '/vendor/autoload.php')) {
    chdir(dirname(dirname(__DIR__)));
} else {
    throw new \Exception("Can't find 'vendor/autoload.php' file");
}

require 'vendor/autoload.php';

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

// prepare tags
$tags = [];
if (!empty($manifestData['tags'])) {
    foreach ($manifestData['tags'] as $tag) {
        $tags[] = $tag['name'];
    }
}

// prepare template path
$templatePath = dirname(__DIR__) . '/template/server';

// create generator config
file_put_contents('openapi_config.json', json_encode(['invokerPackage' => "$title\\OpenAPI\\V$version", 'srcBasePath' => "src/$title/src/OpenAPI/V$version"]));

// generate
exec("openapi-generator generate -i $manifest -o tmp-openapi -g php-ze-ph -c openapi_config.json -t $templatePath", $output);

// generate config to autoload
$pathHandlerData = yaml_parse(file_get_contents('tmp-openapi/application/config/path_handler.yml'));

$file = 'config/autoload/' . lcfirst($title) . '_v' . $version . '_path_handler.global.php';
$content = "<?php\n\nreturn [\n\Articus\PathHandler\RouteInjection\Factory::class => [\n'paths'=>[\n";
foreach ($pathHandlerData['Articus\PathHandler\RouteInjection\Factory']['paths'] as $path => $handlers) {
    $content .= "'$path' => [\n";
    foreach ($handlers as $handler) {
        $handler = str_replace("\Handler", "\Server\Handler", $handler);
        $content .= "\\$handler::class,\n";
    }
    $content .= "],\n";
}
$content .= "],\n";
$content .= "],\n";

$content .= "'dependencies'=>[\n'invokables'=>[\n";
foreach ($tags as $tag) {
    $content .= "\\$title\\OpenAPI\\V$version\\Server\\Rest\\$tag::class=>\\$title\\OpenAPI\V$version\\Server\\Rest\\$tag::class,\n";
}
$content .= "],\n],\n";
$content .= "];";

file_put_contents($file, $content);

// copy all
exec("cp -R tmp-openapi/src/$title/. src/$title/");

// prepare handlers dir
$handlersDir = "src/$title/src/OpenAPI/V$version/Server/Handler";

// make dirs
exec("mkdir src/$title/src/OpenAPI/V$version/Server && mkdir $handlersDir");

// copy handlers
exec("cp -R src/$title/src/OpenAPI/V$version/Handler/. $handlersDir/");

// update namespace for handlers
foreach (scandir($handlersDir) as $handler) {
    if (!in_array($handler, ['.', '..'])) {
        file_put_contents("$handlersDir/$handler", str_replace("\Handler;", "\Server\Handler;", file_get_contents("$handlersDir/$handler")));
    }
}

// clearing
exec("rm -R src/$title/src/OpenAPI/V$version/Handler");
exec("rm -R tmp-openapi");
exec("rm openapi_config.json");

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
exec("cp $manifest $docsDir/");

/**
 * Generate REST classes
 */
$restDir = "src/$title/src/OpenAPI/V$version/Server/Rest";
if (!file_exists($restDir)) {
    mkdir($restDir, 0777, true);
    sleep(1);
}

foreach ($tags as $tag) {
    // create namespace
    $namespace = (new \Nette\PhpGenerator\PhpNamespace("$title\OpenAPI\V$version\Server\Rest"))
        ->addUse('OpenAPI\Server\Rest\BaseAbstract')
        ->addUse('rollun\Callables\Task\ResultInterface')
        ->addUse('rollun\dic\InsideConstruct');

    // create class
    $class = $namespace->addClass($tag);
    $class->setExtends('OpenAPI\Server\Rest\BaseAbstract');
    $class->addConstant('CONTROLLER_OBJECT', 'Name of service which implements OpenApi logic');
    $class->addComment("Class $tag");
    $class->addProperty('controllerObject')->setProtected()->addComment('@var object');

    // create constructor
    $constructor = $class
        ->addMethod('__construct')
        ->addComment("$tag constructor.")
        ->addComment("")
        ->addComment('@param mixed $controllerObject')
        ->addComment("")
        ->addComment('@throws \ReflectionException')
        ->setBody("InsideConstruct::init(['controllerObject' => self::CONTROLLER_OBJECT]);");
    $constructor->addParameter('controllerObject', null);

    $defaultMethodBody = "throw new \Exception('Not implemented method');\n\n";
    $defaultMethodReturn = 'rollun\Callables\Task\ResultInterface';

    foreach ($pathHandlerData['httpMethods'] as $action => $row) {
        if ($row['className'] == $tag) {
            switch (str_replace(lcfirst($row['className']), '', $action)) {
                case 'Post':
                    $method = $class
                        ->addMethod('post')
                        ->setBody('if (method_exists($this->controllerObject, \'post\')) {' . "\n" . '    $bodyDataArray = (array) $bodyData;' . "\n\n" . '    return $this->controllerObject->post($bodyDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param ' . $row['bodyData'] . ' $bodyData');
                    $method->addParameter('bodyData');
                    break;
                case 'Patch':
                    $method = $class
                        ->addMethod('patch')
                        ->setBody('if (method_exists($this->controllerObject, \'patch\')) {' . "\n" . '    $bodyDataArray = (array) $bodyData;' . "\n" . '    $queryDataArray = (array) $queryData;' . "\n\n" . '    return $this->controllerObject->patch($queryDataArray, $bodyDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param ' . $row['queryData'] . ' $queryData')
                        ->addComment('@param ' . $row['bodyData'] . ' $bodyData');
                    $method->addParameter('queryData');
                    $method->addParameter('bodyData');
                    break;
                case 'Get':
                    $method = $class
                        ->addMethod('get')
                        ->setBody('if (method_exists($this->controllerObject, \'get\')) {' . "\n" . '    $queryDataArray = (array) $queryData;' . "\n\n" . '    return $this->controllerObject->get($queryDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param ' . $row['queryData'] . ' $queryData');
                    $method->addParameter('queryData', null);
                    break;
                case 'Delete':
                    $method = $class
                        ->addMethod('delete')
                        ->setBody('if (method_exists($this->controllerObject, \'delete\')) {' . "\n" . '    $queryDataArray = (array) $queryData;' . "\n\n" . '    return $this->controllerObject->delete($queryDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param ' . $row['queryData'] . ' $queryData');
                    $method->addParameter('queryData', null);
                    break;
                case 'IdGet':
                    $method = $class
                        ->addMethod('getById')
                        ->setBody('if (method_exists($this->controllerObject, \'getById\')) {' . "\n" . '    return $this->controllerObject->getById($id);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc');
                    $method->addParameter('id');
                    break;
                case 'IdPatch':
                    $method = $class
                        ->addMethod('patchById')
                        ->setBody('if (method_exists($this->controllerObject, \'patchById\')) {' . "\n" . '    $bodyDataArray = (array) $bodyData;' . "\n\n" . '    return $this->controllerObject->patchById($id, $bodyDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param ' . $row['bodyData'] . ' $bodyData');
                    $method->addParameter('id');
                    $method->addParameter('bodyData');
                    break;
                case 'IdPut':
                    $method = $class
                        ->addMethod('putById')
                        ->setBody('if (method_exists($this->controllerObject, \'putById\')) {' . "\n" . '    $bodyDataArray = (array) $bodyData;' . "\n\n" . '    return $this->controllerObject->putById($id, $bodyDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param ' . $row['bodyData'] . ' $bodyData');
                    $method->addParameter('id');
                    $method->addParameter('bodyData');
                    break;
                case 'IdDelete':
                    $method = $class
                        ->addMethod('deleteById')
                        ->setBody('if (method_exists($this->controllerObject, \'deleteById\')) {' . "\n" . '    return $this->controllerObject->deleteById($id);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc');
                    $method->addParameter('id');
                    break;
            }
        }
    }

    file_put_contents("$restDir/$tag.php", "<?php\n\n" . (string)$namespace);
}

// show generator messages
foreach ($output as $v) {
    echo $v . PHP_EOL;
}
die();