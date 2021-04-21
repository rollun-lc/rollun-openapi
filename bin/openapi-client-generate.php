<?php

if (file_exists(dirname(__DIR__) . '/vendor/autoload.php')) {
    chdir(dirname(__DIR__));
} elseif (file_exists(dirname(dirname(dirname(dirname(__DIR__)))) . '/vendor/autoload.php')) {
    chdir(dirname(dirname(dirname(dirname(__DIR__)))));
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
        $tags[] = ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $tag['name']))));
    }
}

foreach ($manifestData['paths'] as $path) {
    foreach ($path as $method) {
        if (isset($method['tags'])) {
            foreach ($method['tags'] as $tag) {
                $item = ucfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $tag))));
                if (!in_array($item, $tags)) {
                    $tags[] = ucfirst($item);
                }
            }
        }
    }
}

/**
 * Generate DTO
 */
$templatePath = dirname(__DIR__) . '/template/server';
file_put_contents('openapi_config.json', json_encode(['invokerPackage' => "$title\\OpenAPI\\V$version", 'srcBasePath' => "src/$title/src/OpenAPI/V$version"]));
// set CamelCase
$caseOption = "--additional-properties=variableNamingConvention=camelCase";
exec("openapi-generator generate -i $manifest $caseOption -o tmp-openapi -g php-ze-ph -c openapi_config.json -t $templatePath");
$dtoDir = "src/$title/src/OpenAPI/V$version/DTO";
if (!file_exists($dtoDir)) {
    mkdir($dtoDir, 0777, true);
    sleep(1);
}
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
    "openapi-generator generate --global-property apis,apiDocs=false,apiTests=false,models=false,modelDocs=false,modelTests=false,supportingFiles=Configuration.php -i $manifest $caseOption -o tmp -g php -c openapi_client_config.json -t $templatePath",
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
        ->addUse('OpenAPI\Client\Rest\BaseAbstract');

    // prepare api name
    $apiName = "\\$title\\OpenAPI\\V$version\\Client\\Api\\{$tag}Api";

    // create class
    $class = $namespace->addClass($tag);
    $class->setExtends('OpenAPI\Client\Rest\BaseAbstract');
    $class->addComment("Class $tag");
    $class->addProperty('apiName', $apiName)->setProtected()->addComment("@var string");

    // get additional data
    include_once "src/$title/src/OpenAPI/V$version/Client/Configuration.php";
    $configurationClass = "$title\OpenAPI\V$version\Client\Configuration";
    $class->addConstant('CONFIGURATION_CLASS', $configurationClass)->setPublic();
    $additionalData = $configurationClass::$additionalData;

    foreach ($additionalData as $action => $row) {
        if ($row['className'] == $tag . 'Api') {
            $inputParams = [];
            foreach ($row['params'] as $param) {
                if (strpos($param['paramType'], 'OpenAPI') !== false) {
                    $inputParams[] = "\$bodyData";
                } else {
                    $inputParams[] = "\$queryDataObject->{$param['paramName']}";
                }
            }

            // TODO
            if (
                !empty($row['returnType'])
                && !in_array($row['returnType'], ['null','boolean','object','array','number','string'])
                && substr($row['returnType'], -2) !== '[]'
            ) {
                // prepare return DTO type
                $returnType = str_replace("Client\Model", "DTO", $row['returnType']);
                // prepare body template
                $bodyTemplate = "     %s// send request\n\$data = %s;\n\n// validation of response\n\$result = \$this->transfer((array)\$data, $returnType::class);\n\n";
            } else {
                $bodyTemplate = "     %s// send request\n\$result = %s;\n\n";
            }

            $bodyTemplate .= "return \$result;";

            switch (str_replace(lcfirst(str_replace('Api', '', $row['className'])), '', $action)) {
                case 'Post':
                    $bodyType = str_replace("Client\Model", "DTO", $row['params'][0]['paramType']);
                    $body = "// validation of \$bodyData\n\$bodyDataObject = \$this->transfer((array)\$bodyData, '$bodyType');\n\n";

                    $method = $class
                        ->addMethod('post')
                        ->setBody(sprintf($bodyTemplate, $body, "\$this->getApi()->{$action}(" . implode(',', $inputParams) . ")"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('bodyData');
                    break;
                case 'Patch':
                    $queryType = "\\$title\\OpenAPI\\V$version\\DTO\\" . str_replace('Api', '', $row['className']) . "PATCHQueryData";
                    $body = "// validation of \$queryData\n\$queryDataObject = \$this->transfer((array)\$queryData, $queryType::class);\n\n";

                    $bodyType = str_replace("Client\Model", "DTO", $row['params'][0]['paramType']);
                    $body .= "// validation of \$bodyData\n\$bodyDataObject = \$this->transfer((array)\$bodyData, '$bodyType');\n\n";

                    $method = $class
                        ->addMethod('patch')
                        ->setBody(sprintf($bodyTemplate, $body, "\$this->getApi()->{$action}(" . implode(',', $inputParams) . ")"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $queryData')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('queryData');
                    $method->addParameter('bodyData');
                    break;
                case 'Get':
                    $queryType = "\\$title\\OpenAPI\\V$version\\DTO\\" . str_replace('Api', '', $row['className']) . "GETQueryData";
                    $body = "// validation of \$queryData\n\$queryDataObject = \$this->transfer((array)\$queryData, $queryType::class);\n\n";

                    $method = $class
                        ->addMethod('get')
                        ->setBody(sprintf($bodyTemplate, $body, "\$this->getApi()->{$action}(" . implode(',', $inputParams) . ")"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $queryData');
                    $method->addParameter('queryData', []);
                    break;
                case 'Delete':
                    $queryType = "\\$title\\OpenAPI\\V$version\\DTO\\" . str_replace('Api', '', $row['className']) . "DELETEQueryData";
                    $body = "// validation of \$queryData\n\$queryDataObject = \$this->transfer((array)\$queryData, $queryType::class);\n\n";

                    $method = $class
                        ->addMethod('delete')
                        ->setBody(sprintf($bodyTemplate, $body, "\$this->getApi()->{$action}(" . implode(',', $inputParams) . ")"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $queryData');
                    $method->addParameter('queryData', []);
                    break;
                case 'IdGet':
                    $method = $class
                        ->addMethod('getById')
                        ->setBody(sprintf($bodyTemplate, "", "\$this->getApi()->{$action}(\$id)"))
                        ->addComment('@inheritDoc');
                    $method->addParameter('id');
                    break;
                case 'IdPatch':
                    $bodyType = str_replace("Client\Model", "DTO", $row['params'][0]['paramType']);
                    $body = "// validation of \$bodyData\n\$bodyDataObject = \$this->transfer((array)\$bodyData, '$bodyType');\n\n";

                    $method = $class
                        ->addMethod('patchById')
                        ->setBody(sprintf($bodyTemplate, $body, "\$this->getApi()->{$action}(\$id, \$bodyData)"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('id');
                    $method->addParameter('bodyData');
                    break;
                case 'IdPut':
                    $bodyType = str_replace("Client\Model", "DTO", $row['params'][0]['paramType']);
                    $body = "// validation of \$bodyData\n\$bodyDataObject = \$this->transfer((array)\$bodyData, '$bodyType');\n\n";

                    $method = $class
                        ->addMethod('putById')
                        ->setBody(sprintf($bodyTemplate, $body, "\$this->getApi()->{$action}(\$id, \$bodyData)"))
                        ->addComment('@inheritDoc')
                        ->addComment('')
                        ->addComment('@param array $bodyData');
                    $method->addParameter('id');
                    $method->addParameter('bodyData');
                    break;
                case 'IdDelete':
                    $method = $class
                        ->addMethod('deleteById')
                        ->setBody(sprintf($bodyTemplate, "", "\$this->getApi()->{$action}(\$id)"))
                        ->addComment('@inheritDoc');
                    $method->addParameter('id');
                    break;
                default:
                    $params = [];
                    $body = '';

                    $method = $class->addMethod($action)->addComment('@inheritDoc');

                    foreach ($row['params'] as $param) {
                        if (!empty($param['paramType']) && !in_array($param['paramType'], ['null','boolean','object','array','number','string'])) {
                            $paramType = str_replace("Client\Model", "DTO", $param['paramType']);
                            $body .= "// validation of \${$param['paramName']}\n";
                            $body .= "\${$param['paramName']} = \$this->transfer((array)\${$param['paramName']}, $paramType::class);\n\n";
                        }
                        $params[] = "\${$param['paramName']}";
                        if ($param['required']) {
                            $method->addParameter($param['paramName']);
                        } else {
                            $method->addParameter($param['paramName'], null);
                        }
                    }

                    $method->setBody(sprintf(
                        $bodyTemplate,
                        $body,
                        "\$this->getApi()->{$action}(" . implode(', ', $params) . ")"
                    ));
            }
        }
    }

    $method = $class
        ->addMethod('getApi')
        ->setProtected()
        ->setReturnType(\OpenAPI\Client\Api\ApiInterface::class)
        ->setBody("return \$this->api;")
        ->addComment('@return ' . $apiName);

    /*$class->addMethod('getApiName')
        ->setReturnType('string')
        ->setBody('return $this->apiName;')
        ->addComment('@return string');*/


    file_put_contents("$restDir/$tag.php", "<?php\n\n" . (string)$namespace);
}

// show generator messages
foreach (array_merge($output, $output1, $output2, $output3) as $v) {
    echo $v . PHP_EOL;
}
die();
