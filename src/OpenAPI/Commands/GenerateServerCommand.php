<?php


namespace OpenAPI\Commands;


use OpenAPI\Generator\ServerRestGenerator;
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
            $generator = new ServerRestGenerator($this->title, $this->version, $tag, $pathHandlerData);
            $namespace = $generator->generate($tag);

            file_put_contents("$restDir/$tag.php", "<?php\n\n" . $namespace);
        }
    }
}