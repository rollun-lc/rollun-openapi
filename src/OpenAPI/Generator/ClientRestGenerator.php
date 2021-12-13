<?php


namespace OpenAPI\Generator;


use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class ClientRestGenerator
{
    protected $title;

    protected $version;

    protected $tag;

    protected $configuration;

    /**
     * @var PhpNamespace
     */
    protected $namespace;

    /**
     * @var ClassType
     */
    protected $class;

    public function __construct($title, $version, $tag, $configuration)
    {
        $this->title = $title;
        $this->version = $version;
        $this->tag = $tag;
        $this->configuration = $configuration;
    }

    protected function defineMethodParts($data)
    {
        return [
            $data['className'],
            $data['returnType'] ?? null,
            $data['params'] ?? [],
        ];
    }

    /**
     * @param $className
     * @return PhpNamespace
     * @throws \ReflectionException
     */
    public function generate($className)
    {
        $this->namespace = (new PhpNamespace("$this->title\OpenAPI\V$this->version\Client\Rest"))
            ->addUse('OpenAPI\Client\Rest\BaseAbstract');

        // prepare api name
        $apiName = "\\$this->title\\OpenAPI\\V$this->version\\Client\\Api\\{$className}Api";

        $this->class = $this->namespace->addClass($className);
        $this->class->setExtends('OpenAPI\Client\Rest\BaseAbstract');
        $this->class->addComment("Class $className");
        //$this->class->addProperty('apiName', $apiName)->setProtected()->addComment("@var string");
        $this->class->addConstant('API_NAME', $apiName)->setPublic();

        //$this->class->addConstant('CONFIGURATION_CLASS', $this->configuration)->setPublic();
        $actions = (new \ReflectionProperty($this->configuration, 'additionalData'))->getValue();

        foreach ($actions as $methodName => $data) {
            [$apiClass, $returnType, $params] = $this->defineMethodParts($data);

            if ($apiClass !== $this->tag . 'Api') {
                continue;
            }

            $this->addMethod($methodName, $apiClass, $returnType, $params);

            $this->class
                ->addMethod('getApi')
                ->setProtected()
                ->setReturnType(\OpenAPI\Client\Api\ApiInterface::class)
                ->setBody("return \$this->api;")
                ->addComment('@return ' . $apiName);
        }

        return $this->namespace;
    }

    protected function makeParamTypes(?array $params = [])
    {
        $inputParams = [];
        foreach ($params as $param) {
            if (strpos($param['paramType'], 'OpenAPI') !== false) {
                $inputParams[] = "\$bodyData";
            } else {
                $inputParams[] = "\$queryDataObject->{$param['paramName']}";
            }
        }

        return $inputParams;
    }

    /**
     * @param $methodName
     * @param $queryType
     *
     * @todo Раньше определялось по API классу, нужно проверить
     */
    protected function makeQueryType($apiClass, $queryType)
    {
        //return "\\$this->title\\OpenAPI\\V$this->version\\DTO\\" . $this->tag . $queryType;
        return "\\$this->title\\OpenAPI\\V$this->version\\DTO\\" . str_replace('Api', '', $apiClass) . $queryType;
    }

    protected function makeBodyValidation($paramName, $className, callable $callback = null)
    {
        $objectName = $paramName . 'Object';
        $result[] = "// validation of \$$paramName";
        $result[] .= "if (\$$paramName instanceof $className) {\n"
            . "    \$$paramName = \$this->toArray(\$$paramName);\n"
        . "}";
        $result[] .= "\$$objectName = \$this->transfer((array)\$$paramName, '$className');\n";

        if ($callback) {
            $result = $callback($result);
        }

        return implode("\n", $result);
    }

    protected function addMethod($methodName, $apiClass, $returnType = null, $params = [])
    {
        // TODO
        if (
            $returnType
            && !in_array($returnType, ['null','boolean','object','array','number','string'])
            && substr($returnType, -2) !== '[]'
        ) {
            // prepare return DTO type
            $returnType = str_replace("Client\Model", "DTO", $returnType);
            // prepare body template
            $template = "     %s\n\n// send request\n\$data = %s;\n\n// validation of response\n\$result = \$this->transfer((array)\$data, $returnType::class);\n\n";
        } else {
            $template = "     %s\n\n// send request\n\$result = %s;\n\n";
        }

        $template .= "return \$result;";

        switch (str_replace(lcfirst(str_replace('Api', '', $apiClass)), '', $methodName)) {
        //switch (str_replace(lcfirst($this->tag), '', $methodName)) {
            case 'Post':
                $this->postMethod($methodName, $template, $params);
                break;
            case 'Patch':
                $this->patchMethod($methodName, $apiClass, $template, $params);
                break;
            case 'Get':
                $this->getMethod($methodName, $apiClass, $template, $params);
                break;
            case 'Delete':
                $this->deleteMethod($methodName, $apiClass, $template, $params);
                break;
            case 'IdGet':
                $this->idGetMethod($methodName, $template, $params);
                break;
            case 'IdPatch':
                $this->idPatchMethod($methodName, $template, $params);
                break;
            case 'IdPut':
                $this->idPutMethod($methodName, $template, $params);
                break;
            case 'IdDelete':
                $this->idDeleteMethod($methodName, $template, $params);
                break;
            default:
                $this->customMethod($methodName, $template, $params);
        }
    }

    protected function postMethod($methodName, $template, $params = [])
    {

        $paramVariables = $this->makeParamTypes($params);
        $body = '';
        if ($paramVariables) {
            $bodyType = str_replace("Client\Model", "DTO", $params[0]['paramType']);
            //$body = "// validation of \$bodyData\n\$bodyDataObject = \$this->transfer((array)\$bodyData, '$bodyType');";
            $body = $this->makeBodyValidation('bodyData', $bodyType);
        }

        $method = $this->class
            ->addMethod('post')
            ->setBody(sprintf($template, $body, "\$this->getApi()->{$methodName}(" . implode(',', $paramVariables) . ")"))
            ->addComment('@inheritDoc')
        ;

        $method->addComment('')
            ->addComment('@param array $bodyData');
        $method->addParameter('bodyData', null);
    }

    protected function patchMethod($methodName, $apiClass, $template, $params)
    {
        $paramVariables = $this->makeParamTypes($params);

        //$queryType = "\\$this->title\\OpenAPI\\V$this->version\\DTO\\" . str_replace('Api', '', $row['className']) . "PATCHQueryData";
        $queryType = $this->makeQueryType($apiClass, 'PATCHQueryData');
        //$body = "// validation of \$queryData\n\$queryDataObject = \$this->transfer((array)\$queryData, $queryType::class);";
        $body = $this->makeBodyValidation('queryData', $queryType);

        $bodyType = str_replace("Client\Model", "DTO",$params[0]['paramType']);
        //$body .= "// validation of \$bodyData\n\$bodyDataObject = \$this->transfer((array)\$bodyData, '$bodyType');";
        $body .= $this->makeBodyValidation('bodyData', $bodyType);

        $method = $this->class
            ->addMethod('patch')
            ->setBody(sprintf($template, $body, "\$this->getApi()->{$methodName}(" . implode(',', $paramVariables) . ")"))
            ->addComment('@inheritDoc')
            ->addComment('')
            ->addComment('@param array $queryData')
            ->addComment('@param array $bodyData');
        $method->addParameter('queryData');
        $method->addParameter('bodyData');
    }

    protected function getMethod($methodName, $apiClass, $template, $params = [])
    {
        $paramVariables = $this->makeParamTypes($params);

        $body = '';
        if ($paramVariables) {
            $queryType = $this->makeQueryType($apiClass, "GETQueryData");
            //$queryType = "\\$this->title\\OpenAPI\\V$this->version\\DTO\\" . str_replace('Api', '', $apiName) . "GETQueryData";
            //$body = "// validation of \$queryData\n\$queryDataObject = \$this->transfer((array)\$queryData, $queryType::class);";

            $body .= $this->makeBodyValidation('queryData', $queryType, function ($result) use ($params) {
                foreach ($params as $param) {
                    if ($param['style'] === 'form' && strpos($param['paramType'], '[]') && $param['explode'] === false) {
                        $code = "if (isset(\$queryData['{$param['paramName']}']) && is_array(\$queryData['{$param['paramName']}'])) {\n"
                            . "    \$queryData['{$param['paramName']}'] = implode(',', \$queryData['{$param['paramName']}']);\n"
                            . "}";
                        array_splice($result, 2, 0, $code);
                    }
                }
                return $result;
            });
        }

        $method = $this->class
            ->addMethod('get')
            ->setBody(sprintf($template, $body, "\$this->getApi()->{$methodName}(" . implode(', ', $paramVariables) . ")"))
            ->addComment('@inheritDoc');

        if ($paramVariables) {
            $method->addParameter('queryData', []);
            $method->addComment('')
                ->addComment('@param array $queryData');
        }
    }

    protected function deleteMethod($methodName, $apiClass, $template, $params = [])
    {
        $paramVariables = $this->makeParamTypes($params);

        $queryType = $this->makeQueryType($apiClass, 'DELETEQueryData');
        //$queryType = "\\$this->title\\OpenAPI\\V$this->version\\DTO\\" . str_replace('Api', '', $row['className']) . "DELETEQueryData";
        //$body = "// validation of \$queryData\n\$queryDataObject = \$this->transfer((array)\$queryData, $queryType::class);";
        $body = $this->makeBodyValidation('queryData', $queryType);

        $method = $this->class
            ->addMethod('delete')
            ->setBody(sprintf($template, $body, "\$this->getApi()->{$methodName}(" . implode(', ', $paramVariables) . ")"))
            ->addComment('@inheritDoc')
            ->addComment('')
            ->addComment('@param array $queryData');
        $method->addParameter('queryData', []);
    }

    protected function idGetMethod($methodName, $template, $params = [])
    {
        $method = $this->class
            ->addMethod('getById')
            ->setBody(sprintf($template, "", "\$this->getApi()->{$methodName}(\$id)"))
            ->addComment('@inheritDoc');
        $method->addParameter('id');
    }

    protected function idPatchMethod($methodName, $template, $params = [])
    {
        $bodyType = str_replace("Client\Model", "DTO", $params[1]['paramType']);
        //$body = "// validation of \$bodyData\n\$bodyDataObject = \$this->transfer((array)\$bodyData, '$bodyType');";
        $body = $this->makeBodyValidation('bodyData', $bodyType);

        $method = $this->class
            ->addMethod('patchById')
            ->setBody(sprintf($template, $body, "\$this->getApi()->{$methodName}(\$id, \$bodyData)"))
            ->addComment('@inheritDoc')
            ->addComment('')
            ->addComment('@param array $bodyData');
        $method->addParameter('id');
        $method->addParameter('bodyData');
    }

    protected function idPutMethod($methodName, $template, $params = [])
    {
        $bodyType = str_replace("Client\Model", "DTO", $params[1]['paramType']);
        //$body = "// validation of \$bodyData\n\$bodyDataObject = \$this->transfer((array)\$bodyData, '$bodyType');";
        $body = $this->makeBodyValidation('bodyData', $bodyType);

        $method = $this->class
            ->addMethod('putById')
            ->setBody(sprintf($template, $body, "\$this->getApi()->{$methodName}(\$id, \$bodyData)"))
            ->addComment('@inheritDoc')
            ->addComment('')
            ->addComment('@param array $bodyData');
        $method->addParameter('id');
        $method->addParameter('bodyData');
    }

    protected function idDeleteMethod($methodName, $template, $params = [])
    {
        $method = $this->class
            ->addMethod('deleteById')
            ->setBody(sprintf($template, "", "\$this->getApi()->{$methodName}(\$id)"))
            ->addComment('@inheritDoc');
        $method->addParameter('id');
    }

    protected function customMethod($methodName, $template, $params = [])
    {
        $body = '';

        $method = $this->class->addMethod($methodName)
            ->addComment('@inheritDoc');

        $paramVariables = [];
        foreach ($params as $param) {
            if (!empty($param['paramType']) && !in_array($param['paramType'], ['null','boolean','object','number','string']) && strpos($param['paramType'], '[]') === false) {
                $paramType = str_replace("Client\Model", "DTO", $param['paramType']);
                //$body .= "// validation of \${$param['paramName']}\n";
                //$body .= "\${$param['paramName']} = \$this->transfer((array)\${$param['paramName']}, $paramType::class);";
                $body .= $this->makeBodyValidation($param['paramName'], $paramType);
            } elseif ($param['style'] === 'form' && strpos($param['paramType'], '[]') && $param['explode'] === false) {
                $body .= "if (is_array(\${$param['paramName']})) {\n"
                    . "    \${$param['paramName']} = implode(',', \${$param['paramName']});\n"
                    . "}\n";
            }
            $paramVariables[] = "\${$param['paramName']}";
            if ($param['required']) {
                $method->addParameter($param['paramName']);
            } else {
                $method->addParameter($param['paramName'], null);
            }
        }

        $method->setBody(sprintf(
            $template,
            $body,
            "\$this->getApi()->{$methodName}(" . implode(', ', $paramVariables) . ")"
        ));
    }
}