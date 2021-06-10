<?php


namespace OpenAPI\Generator;


use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class ServerRestGenerator
{
    protected $title;

    protected $version;

    protected $tag;

    protected $data;

    /**
     * @var PhpNamespace
     */
    protected $namespace;

    /**
     * @var ClassType
     */
    protected $class;

    public function __construct($title, $version, $tag, $data)
    {
        $this->title = $title;
        $this->version = $version;
        $this->tag = $tag;
        $this->data = $data;
    }

    /**
     * @param $className
     * @return PhpNamespace
     * @todo
     */
    public function generate($className)
    {
        // create namespace
        $namespace = (new PhpNamespace("$this->title\OpenAPI\V$this->version\Server\Rest"))
            ->addUse('OpenAPI\Server\Rest\Base7Abstract')
            ->addUse('Psr\Log\LoggerInterface')
            ->addUse('rollun\dic\InsideConstruct');

        // create class
        $class = $namespace->addClass($className);
        $class->setExtends('OpenAPI\Server\Rest\Base7Abstract');
        $class->addConstant('CONTROLLER_OBJECT', $className . $this->version . 'Controller')->setPublic();
        $class->addComment("Class $className");
        $class->addProperty('controllerObject')->setProtected()->addComment('@var object');
        $class->addProperty('logger')->setProtected()->addComment('@var LoggerInterface');

        // create constructor
        $constructor = $class
            ->addMethod('__construct')
            ->addComment("$className constructor.")
            ->addComment("")
            ->addComment('@param mixed $controllerObject')
            ->addComment('@param LoggerInterface|null logger')
            ->addComment("")
            ->addComment('@throws \ReflectionException')
            ->setBody("InsideConstruct::init(['controllerObject' => static::CONTROLLER_OBJECT, 'logger' => LoggerInterface::class]);");
        $constructor->addParameter('controllerObject', null);
        $constructor->addParameter('logger', null);

        $defaultMethodBody = "throw new \Exception('Not implemented method');\n\n";

        foreach ($this->data['httpMethods'] as $action => $row) {
            if ($row['className'] == $className) {
                switch (str_replace(lcfirst($row['className']), '', $action)) {
                    case 'Post':
                        $method = $class
                            ->addMethod('post')
                            ->setBody('if (method_exists($this->controllerObject, \'post\')) {' . "\n"
                                . '    $bodyDataArray = (array) $bodyData;' . "\n\n"
                                . '    return $this->controllerObject->post($bodyDataArray);' . "\n"
                                . '}' . "\n\n"
                                . $defaultMethodBody)
                            ->addComment('@inheritDoc')
                            ->addComment('')
                            ->addComment('@param ' . $row['bodyData'] . ' $bodyData');
                        $method->addParameter('bodyData', null);
                        break;
                    case 'Patch':
                        $method = $class
                            ->addMethod('patch')
                            ->setBody('if (method_exists($this->controllerObject, \'patch\')) {'
                                . "\n" . '    $bodyDataArray = (array) $bodyData;' . "\n"
                                . '    $queryDataArray = (array) $queryData;' . "\n\n"
                                . '    return $this->controllerObject->patch($queryDataArray, $bodyDataArray);' . "\n"
                                . '}' . "\n\n"
                                .$defaultMethodBody)
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
                            ->setBody('if (method_exists($this->controllerObject, \'get\')) {' . "\n"
                                . '    $queryDataArray = (array) $queryData;' . "\n\n"
                                . '    return $this->controllerObject->get($queryDataArray);' . "\n"
                                . '}' . "\n\n"
                                . $defaultMethodBody)
                            ->addComment('@inheritDoc')
                            ->addComment('')
                            ->addComment('@param ' . $row['queryData'] . ' $queryData');
                        $method->addParameter('queryData', []);
                        break;
                    case 'Delete':
                        $method = $class
                            ->addMethod('delete')
                            ->setBody('if (method_exists($this->controllerObject, \'delete\')) {' . "\n"
                                . '    $queryDataArray = (array) $queryData;' . "\n\n"
                                . '    return $this->controllerObject->delete($queryDataArray);' . "\n"
                                . '}' . "\n\n"
                                . $defaultMethodBody)
                            ->addComment('@inheritDoc')
                            ->addComment('')
                            ->addComment('@param ' . $row['queryData'] . ' $queryData');
                        $method->addParameter('queryData', []);
                        break;
                    case 'IdGet':
                        $method = $class
                            ->addMethod('getById')
                            ->setBody('if (method_exists($this->controllerObject, \'getById\')) {' . "\n"
                                . '    return $this->controllerObject->getById($id);' . "\n"
                                . '}' . "\n\n"
                                . $defaultMethodBody)
                            ->addComment('@inheritDoc');
                        $method->addParameter('id');
                        break;
                    case 'IdPatch':
                        $method = $class
                            ->addMethod('patchById')
                            ->setBody('if (method_exists($this->controllerObject, \'patchById\')) {' . "\n"
                                . '    $bodyDataArray = (array) $bodyData;' . "\n\n"
                                . '    return $this->controllerObject->patchById($id, $bodyDataArray);' . "\n"
                                . '}' . "\n\n"
                                . $defaultMethodBody)
                            ->addComment('@inheritDoc')
                            ->addComment('')
                            ->addComment('@param ' . $row['bodyData'] . ' $bodyData');
                        $method->addParameter('id');
                        $method->addParameter('bodyData');
                        break;
                    case 'IdPut':
                        $method = $class
                            ->addMethod('putById')
                            ->setBody('if (method_exists($this->controllerObject, \'putById\')) {' . "\n"
                                . '    $bodyDataArray = (array) $bodyData;' . "\n\n"
                                . '    return $this->controllerObject->putById($id, $bodyDataArray);' . "\n"
                                . '}' . "\n\n"
                                . $defaultMethodBody)
                            ->addComment('@inheritDoc')
                            ->addComment('')
                            ->addComment('@param ' . $row['bodyData'] . ' $bodyData');
                        $method->addParameter('id');
                        $method->addParameter('bodyData');
                        break;
                    case 'IdDelete':
                        $method = $class
                            ->addMethod('deleteById')
                            ->setBody('if (method_exists($this->controllerObject, \'deleteById\')) {' . "\n"
                                . '    return $this->controllerObject->deleteById($id);' . "\n"
                                . '}' . "\n\n"
                                . $defaultMethodBody)
                            ->addComment('@inheritDoc');
                        $method->addParameter('id');
                        break;
                }
            }
        }

        return $namespace;
    }
}