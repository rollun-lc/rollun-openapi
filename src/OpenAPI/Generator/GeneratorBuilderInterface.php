<?php


namespace OpenAPI\Generator;


interface GeneratorBuilderInterface
{
    public function build(): Generator;

    public function reset(): void;

    /**
     * @param string $inputSpec
     * @return GeneratorBuilder
     */
    public function setInputSpec(string $inputSpec): GeneratorBuilder;

    /**
     * @param string $templateDir
     * @return GeneratorBuilder
     */
    public function setTemplateDir(string $templateDir): GeneratorBuilder;

    /**
     * @param string $generatorName
     * @return GeneratorBuilder
     */
    public function setGeneratorName(string $generatorName): GeneratorBuilder;

    /**
     * @param string $config
     * @return GeneratorBuilder
     */
    public function setConfig(string $config): GeneratorBuilder;

    /**
     * @param array $additionalProperties
     * @return GeneratorBuilder
     */
    public function setAdditionalProperties(array $additionalProperties): GeneratorBuilder;

    /**
     * @param array $globalProperties
     * @return GeneratorBuilder
     */
    public function setGlobalProperties(array $globalProperties): GeneratorBuilder;
}