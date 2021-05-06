<?php


namespace OpenAPI\Generator;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateClientCommand extends GenerateCommandAbstract
{
    protected static $defaultName = 'generate:client';

    /**
     * @return string
     */
    public function getSectionName(): string
    {
        return 'client';
    }

    protected function configure()
    {
        parent::configure();

        $this->setDescription('Generate client.')
            ->setHelp('This command allows you to generate client for your manifest');

        $this->addOption('client-template-dir', null, InputOption::VALUE_OPTIONAL, 'Client template directory');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateDto();

        $this->generateApi();

        $this->generateRest();

        $output->writeln('<info>Done</info>');

        return 0;
    }

    /**
     * @throws \Exception
     */
    protected function generateApi()
    {
        $configFile = $this->createConfigFile("Client");

        $templatePath = $this->getTemplateWithSectionDirPath();
        $tmpDir = $this->getTempGeneratedDirPath();

        $this->generatorBuilder->reset();
        $generator = $this->generatorBuilder
            ->setInputSpec($this->manifest)
            ->setTemplateDir($templatePath)
            ->setGeneratorName('php')
            ->setConfig($configFile)
            ->setOutput($tmpDir)
            ->setGlobalProperties([
                'apis',
                'apiDocs' => false,
                'apiTests' => false,
                'models' => false,
                'modelDocs' => false,
                'modelTests' => false,
                'supportingFiles' => 'Configuration.php'
            ])
            ->setAdditionalProperties([
                'variableNamingConvention' => 'camelCase'
            ])
            ->build();

        $generator->generate($this->outputs);

        // copy
        $tempApiDir = $this->getTempSourceDirPath('Client/Api/.');
        $apiDir = $this->getSourceVersionedWithSectionDirPath('Api');
        $this->makeDirectory($apiDir);
        $this->copy($tempApiDir, $apiDir);
    }

    /**
     * @todo
     */
    protected function generateRest()
    {
        $tmpConfigFile = $this->getTempSourceDirPath('Client/Configuration.php');
        $srcConfigFile = $this->getSourceVersionedWithSectionDirPath('Configuration.php');
        $this->copy($tmpConfigFile, $srcConfigFile);
        /**
         * Generate REST classes
         */
        $restDir = $this->getSourceVersionedWithSectionDirPath('Rest');
        $this->makeDirectory($restDir);

        // TODO
        foreach ($this->tags as $tag) {
            // create namespace
            $namespace = (new \Nette\PhpGenerator\PhpNamespace("$this->title\OpenAPI\V$this->version\Client\Rest"))
                ->addUse('OpenAPI\Client\Rest\BaseAbstract');

            // prepare api name
            $apiName = "\\$this->title\\OpenAPI\\V$this->version\\Client\\Api\\{$tag}Api";

            // create class
            $class = $namespace->addClass($tag);
            $class->setExtends('OpenAPI\Client\Rest\BaseAbstract');
            $class->addComment("Class $tag");
            $class->addProperty('apiName', $apiName)->setProtected()->addComment("@var string");

            // get additional data
            include_once $srcConfigFile;
            $configurationClass = "$this->title\OpenAPI\V$this->version\Client\Configuration";
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
                            $queryType = "\\$this->title\\OpenAPI\\V$this->version\\DTO\\" . str_replace('Api', '', $row['className']) . "PATCHQueryData";
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
                            $queryType = "\\$this->title\\OpenAPI\\V$this->version\\DTO\\" . str_replace('Api', '', $row['className']) . "GETQueryData";
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
                            $queryType = "\\$this->title\\OpenAPI\\V$this->version\\DTO\\" . str_replace('Api', '', $row['className']) . "DELETEQueryData";
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

            $class
                ->addMethod('getApi')
                ->setProtected()
                ->setReturnType(\OpenAPI\Client\Api\ApiInterface::class)
                ->setBody("return \$this->api;")
                ->addComment('@return ' . $apiName);


            file_put_contents("$restDir/$tag.php", "<?php\n\n" . (string)$namespace);
        }
    }
}