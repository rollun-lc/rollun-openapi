<?php


namespace OpenAPI\Generator;


class GeneratorBuilder implements GeneratorBuilderInterface
{
    /**
     * @var string
     */
    protected $inputSpec;

    /**
     * @var string
     */
    protected $templateDir;

    /**
     * @var string
     */
    protected $generatorName;

    /**
     * @var string
     */
    protected $config;

    /**
     * @var string
     */
    protected $output;

    /**
     * @var array
     */
    protected $additionalProperties;

    /**
     * @var array
     */
    protected $globalProperties;

    /**
     * @return Generator
     *
     * @throws \Exception
     */
    public function build(): Generator
    {
        // TODO Make default options
        if (!$this->inputSpec || !$this->templateDir || !$this->generatorName || !$this->config || !$this->output) {
            throw new \Exception('params input-spec, template-dir, generator-name, config are required');
        }

        $generator = new Generator(
            $this->inputSpec,
            $this->templateDir,
            $this->generatorName,
            $this->config,
            $this->output,
            $this->additionalProperties,
            $this->globalProperties
        );

        $this->reset();;

        return $generator;
    }

    public function reset(): void
    {
        $this->inputSpec = $this->templateDir = $this->generatorName = $this->config = $this->output = null;
        $this->additionalProperties = $this->globalProperties = [];
    }

    /**
     * @param string $inputSpec
     *
     * @return GeneratorBuilder
     */
    public function setInputSpec(string $inputSpec): GeneratorBuilder
    {
        $this->inputSpec = $inputSpec;
        return $this;
    }

    /**
     * @param string $templateDir
     *
     * @return GeneratorBuilder
     */
    public function setTemplateDir(string $templateDir): GeneratorBuilder
    {
        $this->templateDir = $templateDir;
        return $this;
    }

    /**
     * @param string $generatorName
     *
     * @return GeneratorBuilder
     */
    public function setGeneratorName(string $generatorName): GeneratorBuilder
    {
        $this->generatorName = $generatorName;
        return $this;
    }

    /**
     * @param string $config
     *
     * @return GeneratorBuilder
     */
    public function setConfig(string $config): GeneratorBuilder
    {
        $this->config = $config;
        return $this;
    }

    /**
     * @param array $additionalProperties
     *
     * @return GeneratorBuilder
     */
    public function setAdditionalProperties(array $additionalProperties): GeneratorBuilder
    {
        $this->additionalProperties = $additionalProperties;
        return $this;
    }

    /**
     * @param array $globalProperties
     *
     * @return GeneratorBuilder
     */
    public function setGlobalProperties(array $globalProperties): GeneratorBuilder
    {
        $this->globalProperties = $globalProperties;
        return $this;
    }

    /**
     * @param string $output
     *
     * @return GeneratorBuilder
     */
    public function setOutput(string $output): GeneratorBuilder
    {
        $this->output = $output;
        return $this;
    }
}