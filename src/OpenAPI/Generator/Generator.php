<?php


namespace OpenAPI\Generator;


class Generator
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
     * Generator constructor.
     * @param string $inputSpec
     * @param string $templateDir
     * @param string $generatorName
     * @param string $config
     * @param string $output
     * @param array|null $additionalProperties
     * @param array|null $globalProperties
     */
    public function __construct(
        string $inputSpec,
        string $templateDir,
        string $generatorName,
        string $config,
        string $output,
        ?array $additionalProperties = [],
        ?array $globalProperties = []
    ) {
        $this->inputSpec = $inputSpec;
        $this->templateDir = $templateDir;
        $this->generatorName = $generatorName;
        $this->config = $config;
        $this->output = $output;
        $this->additionalProperties = $additionalProperties ?? [];
        $this->globalProperties = $globalProperties ?? [];
    }

    /**
     * @param $output
     * @return false|string
     */
    public function generate(&$output)
    {
        $command = $this->prepareCommand();
        return exec($command, $output);
    }

    /**
     * @return string
     */
    protected function prepareCommand()
    {
        $command = 'openapi-generator generate'
            . ' --input-spec ' . $this->inputSpec
            . ' --template-dir ' . $this->templateDir
            . ' --generator-name ' . $this->generatorName
            . ' --config ' . $this->config
            . ' --output ' . $this->output;

        if (!empty($this->additionalProperties)) {
            $command .= ' --additional-properties ' . $this->arrayOptionsToString($this->additionalProperties);
        }

        if (!empty($this->globalProperties)) {
            $command .= ' --global-property ' . $this->arrayOptionsToString($this->globalProperties);
        }

        return $command;
    }

    /**
     * @param array $params
     *
     * @return string
     */
    protected function arrayOptionsToString(array $params)
    {
        return implode(',', array_map(
            function ($v, $k) {
                if (is_string($k)) {
                    if (is_bool($v)) {
                        $v = $v ? 'true': 'false';
                    }
                    return sprintf("%s=%s", $k, $v);
                }
                return  $v;
            },
            $params,
            array_keys($params)
        ));
    }
}