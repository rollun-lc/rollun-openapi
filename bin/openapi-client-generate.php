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

// create generator config
file_put_contents(
    'openapi_client_config.json', json_encode(['invokerPackage' => "$title\\OpenAPI\\V$version\\Client", 'srcBasePath' => "src/$title/src/OpenAPI/V$version/Client"])
);
sleep(1);

// prepare template path
$templatePath = dirname(__DIR__) . '/template/client';

// generate
exec(
    "openapi-generator generate --global-property apis,apiDocs=false,apiTests=false,models=false,modelDocs=false,modelTests=false,supportingFiles=Configuration.php -i $manifest -o tmp -g php -c openapi_client_config.json -t $templatePath",
    $output
);

// copy
exec("cp -R tmp/src/. src/", $output1);

// clearing
exec("rm -R tmp", $output2);
exec("rm openapi_client_config.json", $output3);

/**
 * Generate REST classes
 */
$restDir = "src/$title/src/OpenAPI/V$version/Client/Rest";
if (!file_exists($restDir)) {
    mkdir($restDir, 0777, true);
    sleep(1);
}

foreach ($tags as $tag) {
    // create namespace
    $namespace = (new \Nette\PhpGenerator\PhpNamespace("$title\OpenAPI\V$version\Client\Rest"))
        ->addUse('OpenAPI\Server\Rest\BaseAbstract')
        ->addUse('rollun\Callables\Task\ResultInterface')
        ->addUse('rollun\Callables\Task\Result')
        ->addUse('GuzzleHttp\Client')
        ->addUse("$title\OpenAPI\V$version\Client\Api\\{$tag}Api");

    // create class
    $class = $namespace->addClass($tag);
    $class->setExtends('OpenAPI\Server\Rest\BaseAbstract');
    $class->addComment("Class $tag");
    $class->addProperty('api')->setProtected()->addComment("@var {$tag}Api");
    $class->addConstant('IS_API_CLIENT', true);

    // create constructor
    $constructor = $class
        ->addMethod('__construct')
        ->addComment("$tag constructor.")
        ->addComment("")
        ->addComment('@param string|null $lifeCycleToken')
        ->setBody("\n\$this->api = new {$tag}Api(new Client(['headers' => ['LifeCycleToken' => \$lifeCycleToken]]));");
    $constructor->addParameter('lifeCycleToken');

    // get additional data
    include_once "src/$title/src/OpenAPI/V$version/Client/Configuration.php";
    $configurationClass = "\\$title\OpenAPI\V$version\Client\Configuration";
    $additionalData = $configurationClass::$additionalData;

    $defaultMethodBody = "throw new \Exception('Not implemented method');\n\n";
    $defaultMethodReturn = 'rollun\Callables\Task\ResultInterface';

    foreach ($additionalData as $action => $row) {
        if ($row['className'] == $tag . 'Api') {
            $inputParams = [];
            foreach ($row['params'] as $param) {
                if (strpos($param['paramType'], 'OpenAPI') !== false){
                    $inputParams[] = "new {$param['paramType']}(\$bodyData)";
                }else{
                    $inputParams[] = "\$queryData['{$param['paramName']}']";
                }
            }

            $body = "    \$result = \$this->api->{$action}(" . implode(',', $inputParams) . ");\n\nreturn new Result(\$result['data'], \$result['messages']);";

            switch (str_replace(lcfirst(str_replace('Api', '', $row['className'])), '', $action)) {
                case 'Post':
                    $method = $class
                        ->addMethod('post')
                        ->setBody($body)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('bodyData');
                    break;
                case 'Patch':
                    $method = $class
                        ->addMethod('patch')
                        ->setBody($body)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $queryData')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('queryData');
                    $method->addParameter('bodyData');
                    break;
                case 'Get':
                    $method = $class
                        ->addMethod('get')
                        ->setBody($body)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $queryData');
                    $method->addParameter('queryData', null);
                    break;
                case 'Delete':
                    $method = $class
                        ->addMethod('delete')
                        ->setBody($body)
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $queryData');
                    $method->addParameter('queryData', null);
                    break;
                case 'IdGet':
                    $method = $class
                        ->addMethod('getById')
                        ->setBody("    \$result = \$this->api->{$action}(\$id);\n\nreturn new Result(\$result['data'], \$result['messages']);")
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc');
                    $method->addParameter('id');
                    break;
                case 'IdPatch':
                    $method = $class
                        ->addMethod('patchById')
                        ->setBody("    \$result = \$this->api->{$action}(\$id, new {$row['params'][1]['paramType']}(\$bodyData));\n\nreturn new Result(\$result['data'], \$result['messages']);")
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('id');
                    $method->addParameter('bodyData');
                    break;
                case 'IdPut':
                    $method = $class
                        ->addMethod('putById')
                        ->setBody("    \$result = \$this->api->{$action}(\$id, new {$row['params'][1]['paramType']}(\$bodyData));\n\nreturn new Result(\$result['data'], \$result['messages']);")
                        ->setReturnType($defaultMethodReturn)
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('id');
                    $method->addParameter('bodyData');
                    break;
                case 'IdDelete':
                    $method = $class
                        ->addMethod('deleteById')
                        ->setBody("    \$result = \$this->api->{$action}(\$id);\n\nreturn new Result(\$result['data'], \$result['messages']);")
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
foreach (array_merge($output, $output1, $output2, $output3) as $v) {
    echo $v . PHP_EOL;
}
die();