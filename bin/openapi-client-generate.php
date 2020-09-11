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

/**
 * Generate DTO
 */
$templatePath = dirname(__DIR__) . '/template/server';
file_put_contents('openapi_config.json', json_encode(['invokerPackage' => "$title\\OpenAPI\\V$version", 'srcBasePath' => "src/$title/src/OpenAPI/V$version"]));
exec("openapi-generator generate -i $manifest -o tmp-openapi -g php-ze-ph -c openapi_config.json -t $templatePath");
exec("mkdir src/$title/src/OpenAPI/V$version/DTO");
exec("cp -R tmp-openapi/src/$title/src/OpenAPI/V$version/DTO/. src/$title/src/OpenAPI/V$version/DTO/");
exec("rm -R tmp-openapi");
exec("rm openapi_config.json");

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
exec("rm -R src/$title/src/OpenAPI/V$version/Client/Model", $output2);
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
        ->addUse('GuzzleHttp\Client')
        ->addUse('rollun\dic\InsideConstruct')
        ->addUse('Articus\DataTransfer\Service', 'DataTransferService')
        ->addUse("$title\OpenAPI\V$version\Client\Api\\{$tag}Api");

    // create class
    $class = $namespace->addClass($tag);
    $class->setExtends('OpenAPI\Server\Rest\BaseAbstract');
    $class->addComment("Class $tag");
    $class->addProperty('api')->setProtected()->addComment("@var {$tag}Api");
    $class->addProperty('dt')->setProtected()->addComment("@var DataTransferService");
    $class->addConstant('IS_API_CLIENT', true);

    // create constructor
    $constructor = $class
        ->addMethod('__construct')
        ->addComment("$tag constructor.")
        ->addComment("")
        ->addComment('@param string|null $lifeCycleToken')
        ->addComment('@param DataTransferService|null $dt')
        ->setBody(
            "\n\$this->api = new {$tag}Api(new Client(['headers' => ['LifeCycleToken' => \$lifeCycleToken]]));\nInsideConstruct::init(['dt' => DataTransferService::class]);"
        );
    $constructor->addParameter('lifeCycleToken');
    $constructor->addParameter('dt', null);

    // get additional data
    include_once "src/$title/src/OpenAPI/V$version/Client/Configuration.php";
    $configurationClass = "\\$title\OpenAPI\V$version\Client\Configuration";
    $additionalData = $configurationClass::$additionalData;

    foreach ($additionalData as $action => $row) {
        if ($row['className'] == $tag . 'Api') {
            $inputParams = [];
            foreach ($row['params'] as $param) {
                if (strpos($param['paramType'], 'OpenAPI') !== false) {
                    $inputParams[] = "\$bodyData";
                } else {
                    $inputParams[] = "\$queryData['{$param['paramName']}']";
                }
            }

            // prepare query type
            $queryType = "\\$title\\OpenAPI\\V$version\\DTO\\" . str_replace("Patch", "", $action) . "PATCHQueryData";

            // prepare return DTO type
            $returnType = str_replace("Client\Model", "DTO", $row['returnType']);

            // prepare body template
            $bodyTemplate = "     %s\$data = %s;\n\$result = new $returnType();\n\n\$errors = \$this->dt->transfer(\$data, \$result);\nif (!empty(\$errors)) {\n";
            $bodyTemplate .= "    throw new \Exception('Validation of response is failed! Details: '. json_encode(\$errors));\n}\n\nreturn \$result;";

            switch (str_replace(lcfirst(str_replace('Api', '', $row['className'])), '', $action)) {
                case 'Post':
                    $bodyType = str_replace("Client\Model", "DTO", $row['params'][0]['paramType']);

                    $body = "\$errors = \$this->dt->transfer(\$bodyData, new $bodyType());\n";
                    $body .= "if (!empty(\$errors)) {\n";
                    $body .= "    throw new \Exception('Validation of request is failed! Details: '. json_encode(\$errors));\n";
                    $body .= "}\n\n";

                    $method = $class
                        ->addMethod('post')
                        ->setBody(sprintf($bodyTemplate, $body, "\$this->api->{$action}(" . implode(',', $inputParams) . ")"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('bodyData');
                    break;
                case 'Patch':
                    $bodyType = str_replace("Client\Model", "DTO", $row['params'][1]['paramType']);
//
//                    $body = "\$errors = \$this->dt->transfer(\$bodyData, new $bodyType());\n";
//                    $body .= "if (!empty(\$errors)) {\n";
//                    $body .= "    throw new \Exception('Validation of request is failed! Details: '. json_encode(\$errors));\n";
//                    $body .= "}\n\n";

                    $method = $class
                        ->addMethod('patch')
                        ->setBody(sprintf($bodyTemplate, "", "\$this->api->{$action}(" . implode(',', $inputParams) . ")"))
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
                        ->setBody(sprintf($bodyTemplate, "", "\$this->api->{$action}(" . implode(',', $inputParams) . ")"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $queryData');
                    $method->addParameter('queryData', null);
                    break;
                case 'Delete':
                    $method = $class
                        ->addMethod('delete')
                        ->setBody(sprintf($bodyTemplate, "", "\$this->api->{$action}(" . implode(',', $inputParams) . ")"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $queryData');
                    $method->addParameter('queryData', null);
                    break;
                case 'IdGet':
                    $method = $class
                        ->addMethod('getById')
                        ->setBody(sprintf($bodyTemplate, "", "\$this->api->{$action}(\$id)"))
                        ->addComment('@inheritDoc');
                    $method->addParameter('id');
                    break;
                case 'IdPatch':
                    $method = $class
                        ->addMethod('patchById')
                        ->setBody(sprintf($bodyTemplate, "", "\$this->api->{$action}(\$id, new {$row['params'][1]['paramType']}(\$bodyData))"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('id');
                    $method->addParameter('bodyData');
                    break;
                case 'IdPut':
                    $method = $class
                        ->addMethod('putById')
                        ->setBody(sprintf($bodyTemplate, "", "\$this->api->{$action}(\$id, new {$row['params'][1]['paramType']}(\$bodyData))"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('id');
                    $method->addParameter('bodyData');
                    break;
                case 'IdDelete':
                    $method = $class
                        ->addMethod('deleteById')
                        ->setBody(sprintf($bodyTemplate, "", "\$this->api->{$action}(\$id)"))
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