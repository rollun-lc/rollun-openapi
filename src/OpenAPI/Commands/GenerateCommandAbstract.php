<?php


namespace OpenAPI\Commands;


use OpenAPI\Generator\GeneratorBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

abstract class GenerateCommandAbstract extends Command
{
    protected const QUESTION_MANIFEST = 'Please set path to openapi manifest (openapi.yaml by default):';

    protected const DEFAULT_MANIFEST = 'openapi.yaml';

    protected const CONFIG_FILE = 'openapi_config.json';

    protected const DIR_TEMPLATE = 'template';

    protected const DIR_TEMPORARY = 'temp-openapi';

    protected const DIR_DOCS = 'public/openapi/docs';

    /**
     * @var string
     */
    protected $manifest;

    /**
     * @var array
     */
    protected $manifestData;

    /**
     * @var GeneratorBuilderInterface
     */
    protected $generatorBuilder;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $version;

    /**
     * @var array
     */
    protected $tags;

    /**
     * @var array
     */
    protected $outputs = [];

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * GenerateCommand constructor.
     * @param string|null $name
     */
    public function __construct(GeneratorBuilderInterface $generatorBuilder, string $name = null)
    {
        parent::__construct($name);

        $this->setCode(function(InputInterface $input, OutputInterface $output){
            return $this->parentExecute($input, $output);
        });

        $this->generatorBuilder = $generatorBuilder;
    }

    /**
     * @return string
     */
    abstract protected function getSectionName(): string;

    protected function configure()
    {
        $this->addOption(
            'manifest',
            'm',
            InputOption::VALUE_OPTIONAL,
            'Location of the OpenAPI spec, as URL or file'
        );

        $this->addOption(
            'debug',
            'd',
            InputOption::VALUE_OPTIONAL,
            'Show output'
        );

        /*$this->addOption(
            'template-dir',
            't',
            InputArgument::OPTIONAL,
            'Path to template directory',
            self::DIR_TEMPLATE
        );
        $this->addOption(
            'Where to write the generated files',
            null,
            InputArgument::OPTIONAL,
            'Folder containing the template files'
        );*/
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function parentExecute(InputInterface $input, OutputInterface $output)
    {
        try {
            $this->before($input, $output);
            return $this->execute($input, $output);
        } catch (\Throwable $exception) {
            $output->writeln('<error>' . $exception->getMessage() . '</error>');
            $output->writeln('Exit');
            return 1;
        } finally {
            $this->after($input, $output);
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @throws \Exception
     */
    protected function before(InputInterface $input, OutputInterface $output)
    {
        $manifest = $input->getOption('manifest');
        if (empty($manifest)) {
            $helper = $this->getHelper('question');
            $question = new Question(self::QUESTION_MANIFEST, self::DEFAULT_MANIFEST);

            $manifest = $helper->ask($input, $output, $question);
        }
        $this->manifest = $manifest;

        $debug = $input->getOption('debug');
        if ($debug === 'true') {
            $this->debug = true;
        }

        $manifestData = $this->loadManifest();
        if ($manifestData) {
            $this->manifestData = $manifestData;
            $this->defineData($manifestData);
        }

        $this->removeDirectory($this->getTempDirPath());
        $this->makeDirectory($this->getTempGeneratedDirPath());
        file_put_contents($this->getTempDirPath('.gitignore'), '*');

        return true;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function after(InputInterface $input, OutputInterface $output)
    {
        if ($this->debug) {
            foreach ($this->outputs as $item) {
                echo $item;
            }
        }
        $this->removeDirectory(self::DIR_TEMPORARY);
    }

    /**
     * @return false|mixed
     *
     * @throws \Exception
     */
    protected function loadManifest()
    {
        if (!filter_var($this->manifest, FILTER_VALIDATE_URL) && !file_exists($this->manifest)) {
            throw new \Exception('Openapi manifest file doesn\'t exists!');
        }

        // parse manifest
        if (strpos($this->manifest, '.yaml') !== false || strpos($this->manifest, '.yml') !== false) {
            $manifestData = yaml_parse(file_get_contents($this->manifest));
        } elseif (strpos($this->manifest, '.json') !== false) {
            $manifestData = json_decode(file_get_contents($this->manifest), true);
        } else {
            throw new \Exception('Unknown openapi manifest file format! Only yaml or json allowed.');
        }

        if (!isset($manifestData['info']['title'])) {
            throw new \Exception('Title is required for openapi manifest.');
        }

        return $manifestData;
    }

    /**
     * @param null $suffix
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function createConfigFile($suffix = null)
    {
        $invokerPackage = "$this->title\\OpenAPI\\V$this->version";
        $srcBasePath = $this->getSourceVersionedDirPath();
        if ($suffix) {
            $invokerPackage .= '\\' . $suffix;
            $srcBasePath .= '/' . $suffix;
        }
        $configFile = self::DIR_TEMPORARY . '/' . self::CONFIG_FILE;
        if (file_put_contents($configFile, json_encode([
            'invokerPackage' => $invokerPackage,
            'srcBasePath' => $srcBasePath,
            'currentVersion' => $this->version,
        ]))) {
            return $configFile;
        }

        throw new \Exception('Can not create config file');
    }

    /**
     * Generate DTO
     */
    protected function generateDto()
    {
        $configFile = $this->createConfigFile();
        $tmpDir = $this->getTempGeneratedDirPath();

        $generator = $this->generatorBuilder
            ->setInputSpec($this->manifest)
            ->setTemplateDir($this->getTemplateDirPath('server'))
            ->setGeneratorName('php-mezzio-ph')
            ->setConfig($configFile)
            ->setOutput($tmpDir)
            ->setAdditionalProperties([
                'variableNamingConvention' => 'camelCase'
            ])
            ->build();

        $generator->generate($this->outputs);

        $dtoDir = $this->getSourceVersionedDirPath('DTO');
        $this->makeDirectory($dtoDir);

        // TODO
        sleep(1);

        $tmpDir = $this->getTempSourceDirPath('DTO/.');
        $this->copy($tmpDir, $dtoDir);
    }

    /**
     * Define title, version and tags
     *
     * @param $manifestData
     */
    protected function defineData($manifestData)
    {
        $this->title = $this->defineTitle($manifestData);
        $this->version = $this->defineVersion($manifestData);
        $this->tags = $this->defineTags($manifestData);
    }

    /**
     * Define title
     *
     * @param array $manifestData
     *
     * @return array|string|string[]|null
     */
    protected function defineTitle($manifestData)
    {
        return preg_replace("/[^a-zA-Z0-9]/", '', $manifestData['info']['title']);
    }

    /**
     * Define version
     *
     * @param array $manifestData
     *
     * @return array|string|string[]|null
     */
    protected function defineVersion($manifestData)
    {
        return $this->modifyVersion($manifestData['info']['version']);
    }

    protected function modifyVersion($version)
    {
        return preg_replace("/([0-9]+)\./", '$1_', ltrim($version, 'v'));
    }

    /**
     * Define tags
     *
     * @param array $manifestData
     *
     * @return array
     */
    protected function defineTags($manifestData)
    {
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

        return $tags;
    }

    /**
     * @param $from
     * @param $to
     *
     * @return false|string
     */
    protected function copy($from, $to) {
        $output = $this->debug ? $this->outputs : null;
        return exec("cp -R $from $to", $output);
    }

    /**
     * @param $dir
     * @param int $permissions
     */
    protected function makeDirectory($dir, $permissions = 0777)
    {
        if (!file_exists($dir) && !mkdir($dir, $permissions, true) && !is_dir($dir)) {
            throw new \RuntimeException(sprintf('Directory "%s" was not created', self::DIR_TEMPORARY));
        }
    }

    /**
     * @param $dir
     * @todo проверять вывод
     */
    protected function removeDirectory($dir)
    {
        if (file_exists($dir) && is_dir($dir)) {
            exec("rm -rf $dir", $this->outputs);
        }
    }

    /**
     * Возвращает путь директории
     * Используется другими методами, которые возвращают пути директорий
     *
     * @param $dir
     * @param string $path
     * @return mixed|string
     */
    protected function getDirPath($dir, $path = '')
    {
        $dir = rtrim($dir, '/');
        if ($path) {
            $dir .= '/' . ltrim($path, '/');
        }

        return $dir;
    }

    /**
     * Возвращает путь версионной директории опенапи
     * Типа, src/HelloUser/src/Openapi/V2
     *
     * @param string $path
     * @return mixed|string
     */
    protected function getSourceVersionedDirPath($path = '')
    {
        return $this->getDirPath(
            "src/$this->title/src/OpenAPI/V$this->version",
            $path
        );
    }

    /**
     * Возвращает временную директорию
     * По умолчанию - temp-openapi
     *
     * @return string
     */
    protected function getTempDirPath($path = '')
    {
        return $this->getDirPath(self::DIR_TEMPORARY, $path);
    }

    /**
     * Возвращает общую директорию шаблонов
     * По умолчанию - template
     *
     * @param string $path
     *
     * @return string
     */
    protected function getTemplateDirPath($path = '')
    {
        return $this->getDirPath(__DIR__ . '/../../../' . self::DIR_TEMPLATE, $path);
    }

    /**
     * Возвращает путь публичной директории документации
     * По умолчанию - public/openapi/docs
     *
     * @param string $path
     *
     * @return mixed|string
     */
    protected function getDocDirPath($path = '')
    {
        return $this->getDirPath(self::DIR_DOCS, $path);
    }

    /**
     * Возвращает путь версионной директории документации
     * Например, public/openapi/docs/HelloUser/v2
     *
     * @param string $path
     * @return mixed|string
     */
    protected function getDocVersionedDirPath($path = '')
    {
        return $this->getDirPath(
            $this->getDocDirPath(
                $this->title . '/v' . $this->version
            ),
            $path
        );
    }

    /**
     * Возвращает директорию опенапи исходного кода для конкретной части приложения (клиент-сервер)
     * Например, src/HelloUser/src/OpenAPI/V1/Client
     *
     * @param string $path
     * @return mixed|string
     */
    protected function getSourceVersionedWithSectionDirPath($path = '')
    {
        return $this->getDirPath(
            $this->getDirPath(
                $this->getSourceVersionedDirPath(),
                ucfirst($this->getSectionName())
            ),
            $path
        );
    }

    /**
     * Возвращает директорию шаблонов конкретной части приложения (клиент-сервер)
     *
     * @param string $path
     * @return mixed|string
     */
    protected function getTemplateWithSectionDirPath($path = '')
    {
        return $this->getDirPath(
            $this->getDirPath(
                $this->getTemplateDirPath(),
                $this->getSectionName()),
            $path
        );
    }

    /**
     * @param string $path
     *
     * @return mixed|string
     *
     * @todo
     */
    protected function getTempGeneratedDirPath($path = '')
    {
        return $this->getDirPath(
            $this->getDirPath(
                $this->getTempDirPath(),
                'generated'
            ),
            $path
        );
    }

    /**
     * Возвращает директорию исходных кодов временной директории
     *
     * @param string $path
     *
     * @return mixed|string
     */
    protected function getTempSourceDirPath($path = '')
    {
        return $this->getDirPath(
            $this->getDirPath(
                $this->getTempGeneratedDirPath(),
                $this->getSourceVersionedDirPath()
            ),
            $path
        );
    }
}