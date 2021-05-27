<?php


namespace OpenAPI\Commands;


use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateServerCommand extends GenerateCommandAbstract
{
    protected static $defaultName = 'generate:server';

    protected function configure()
    {
        parent::configure();

        $this->addOption(
            'controllerObject',
            null,
            InputOption::VALUE_OPTIONAL,
            'Location of the OpenAPI spec, as URL or file'
        );
    }

    /**
     * @return string
     */
    protected function getSectionName(): string
    {
        return 'server';
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->generateDto();

        $this->crateAutoloadFile();

        $this->copyHandlers();

        $this->copyDocs();

        $this->generateRests();

        $output->writeln('<info>Done</info>');

        return 0;
    }

    protected function copyDocs()
    {
        // create api docs
        $docsDir = $this->getDocVersionedDirPath();
        $this->makeDirectory($docsDir);

        // create docs index.html
        $tempDocIndexPath = $this->getTemplateWithSectionDirPath('docs/index.html');;
        $html = file_get_contents($tempDocIndexPath);
        $html = str_replace('{{title}}', $this->title, $html);
        $manifestParts = explode("/", $this->manifest);
        $manifestName = array_pop($manifestParts);
        $html = str_replace('{{manifest}}', $manifestName, $html);

        file_put_contents($docsDir . '/index.html', $html);

        // copy
        $manifestContent = file_get_contents($this->manifest);
        file_put_contents($docsDir . '/' . $manifestName, $manifestContent);
        //$this->copy($this->manifest, $docsDir);
    }

    protected function copyHandlers()
    {
        // prepare handlers dir
        $handlerDir = $this->getSourceVersionedWithSectionDirPath('Handler');
        $this->makeDirectory($handlerDir);
        $handlerTempDir = $this->getTempSourceDirPath('Handler/.');

        $this->copy($handlerTempDir, $handlerDir);

        // update namespace for handlers
        // TODO Возможно надо менять в темп директории, а потом копировать
        foreach (scandir($handlerDir) as $handler) {
            if (!in_array($handler, ['.', '..'])) {
                $content = file_get_contents("$handlerDir/$handler");
                $content = str_replace("\Handler;", "\Server\Handler;",
                    $content
                );

                file_put_contents(
                    "$handlerDir/$handler",
                    $content
                );
            }
        }
    }

    protected function crateAutoloadFile()
    {
        // generate config to autoload
        $configFile = $this->getTempGeneratedDirPath('application/config/path_handler.yml');
        $pathHandlerData = yaml_parse(file_get_contents($configFile));

        $filename = 'config/autoload/' . lcfirst($this->title) . '_v' . $this->version . '_path_handler.global.php';
        $content = "<?php\n\n";
        $content .= "return [\n";
        $content .= "    \Articus\PathHandler\RouteInjection\Factory::class => [\n";
        $content .= "        'paths' => [\n";
        foreach ($pathHandlerData['Articus\PathHandler\RouteInjection\Factory']['paths'] as $path => $handlers) {
            $content .= "            '$path' => [\n";
            foreach ($handlers as $handler) {
                $handler = str_replace("\Handler", "\Server\Handler", $handler);
                $content .= "                \\$handler::class,\n";
            }
            $content .= "            ],\n";
        }
        $content .= "        ],\n";
        $content .= "    ],\n";

        $content .= "    'dependencies' => [\n";
        $content .= "        'invokables' => [\n";
        foreach ($this->tags as $tag) {
            $content .= "            \\$this->title\\OpenAPI\\V$this->version\\Server\\Rest\\$tag::class => \\$this->title\\OpenAPI\V$this->version\\Server\\Rest\\$tag::class,\n";
        }
        $content .= "        ],\n";
        $content .= "    ],\n";
        $content .= "];";

        file_put_contents($filename, $content);
    }

    /**
     * @todo
     */
    public function generateRests()
    {
        // TODO Дублируется
        $configFile = $this->getTempGeneratedDirPath('application/config/path_handler.yml');
        $pathHandlerData = yaml_parse(file_get_contents($configFile));

        /**
         * Generate REST classes
         */
        $restDir = $this->getSourceVersionedWithSectionDirPath('Rest');
        $this->makeDirectory($restDir);

        // TODO
        foreach ($this->tags as $tag) {
            // create namespace
            $namespace = (new \Nette\PhpGenerator\PhpNamespace("$this->title\OpenAPI\V$this->version\Server\Rest"))
                ->addUse('OpenAPI\Server\Rest\Base7Abstract')
                ->addUse('Psr\Log\LoggerInterface')
                ->addUse('rollun\dic\InsideConstruct');

            // create class
            $class = $namespace->addClass($tag);
            $class->setExtends('OpenAPI\Server\Rest\Base7Abstract');
            $class->addConstant('CONTROLLER_OBJECT', $tag . 'Controller');
            $class->addComment("Class $tag");
            $class->addProperty('controllerObject')->setProtected()->addComment('@var object');
            $class->addProperty('logger')->setProtected()->addComment('@var LoggerInterface');

            // create constructor
            $constructor = $class
                ->addMethod('__construct')
                ->addComment("$tag constructor.")
                ->addComment("")
                ->addComment('@param mixed $controllerObject')
                ->addComment('@param LoggerInterface|null logger')
                ->addComment("")
                ->addComment('@throws \ReflectionException')
                ->setBody("InsideConstruct::init(['controllerObject' => static::CONTROLLER_OBJECT, 'logger' => LoggerInterface::class]);");
            $constructor->addParameter('controllerObject', null);
            $constructor->addParameter('logger', null);

            $defaultMethodBody = "throw new \Exception('Not implemented method');\n\n";

            foreach ($pathHandlerData['httpMethods'] as $action => $row) {
                if ($row['className'] == $tag) {
                    switch (str_replace(lcfirst($row['className']), '', $action)) {
                        case 'Post':
                            $method = $class
                                ->addMethod('post')
                                ->setBody('if (method_exists($this->controllerObject, \'post\')) {' . "\n" . '    $bodyDataArray = (array) $bodyData;' . "\n\n" . '    return $this->controllerObject->post($bodyDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                                ->addComment('@inheritDoc')
                                ->addComment('')
                                ->addComment('@param ' . $row['bodyData'] . ' $bodyData');
                            $method->addParameter('bodyData', null);
                            break;
                        case 'Patch':
                            $method = $class
                                ->addMethod('patch')
                                ->setBody('if (method_exists($this->controllerObject, \'patch\')) {' . "\n" . '    $bodyDataArray = (array) $bodyData;' . "\n" . '    $queryDataArray = (array) $queryData;' . "\n\n" . '    return $this->controllerObject->patch($queryDataArray, $bodyDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
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
                                ->addComment('@inheritDoc')
                                ->addComment('')
                                ->addComment('@param ' . $row['queryData'] . ' $queryData');
                            $method->addParameter('queryData', []);
                            break;
                        case 'Delete':
                            $method = $class
                                ->addMethod('delete')
                                ->setBody('if (method_exists($this->controllerObject, \'delete\')) {' . "\n" . '    $queryDataArray = (array) $queryData;' . "\n\n" . '    return $this->controllerObject->delete($queryDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                                ->addComment('@inheritDoc')
                                ->addComment('')
                                ->addComment('@param ' . $row['queryData'] . ' $queryData');
                            $method->addParameter('queryData', []);
                            break;
                        case 'IdGet':
                            $method = $class
                                ->addMethod('getById')
                                ->setBody('if (method_exists($this->controllerObject, \'getById\')) {' . "\n" . '    return $this->controllerObject->getById($id);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
                                ->addComment('@inheritDoc');
                            $method->addParameter('id');
                            break;
                        case 'IdPatch':
                            $method = $class
                                ->addMethod('patchById')
                                ->setBody('if (method_exists($this->controllerObject, \'patchById\')) {' . "\n" . '    $bodyDataArray = (array) $bodyData;' . "\n\n" . '    return $this->controllerObject->patchById($id, $bodyDataArray);' . "\n" . '}' . "\n\n" .$defaultMethodBody)
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
                                ->addComment('@inheritDoc');
                            $method->addParameter('id');
                            break;
                    }
                }
            }

            file_put_contents("$restDir/$tag.php", "<?php\n\n" . (string)$namespace);
        }
    }
}