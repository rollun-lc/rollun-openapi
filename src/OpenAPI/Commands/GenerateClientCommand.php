<?php


namespace OpenAPI\Commands;


use OpenAPI\Generator\ClientRestGenerator;
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

        $this->generateRestObjects();

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
    protected function generateRestObjects()
    {
        $tmpConfigFile = $this->getTempSourceDirPath('Client/Configuration.php');
        $srcConfigFile = $this->getSourceVersionedWithSectionDirPath('Configuration.php');
        $this->copy($tmpConfigFile, $srcConfigFile);
        /**
         * Generate REST classes
         */
        $restDir = $this->getSourceVersionedWithSectionDirPath('Rest');
        $this->makeDirectory($restDir);

        // get additional data
        include_once $srcConfigFile;
        $configurationClass = "$this->title\OpenAPI\V$this->version\Client\Configuration";


        // TODO
        foreach ($this->tags as $tag) {

            $generator = new ClientRestGenerator($this->title, $this->version, $tag, $configurationClass);
            $namespace = $generator->generate($tag);

            file_put_contents("$restDir/$tag.php", "<?php\n\n" . (string)$namespace);
        }
    }
}