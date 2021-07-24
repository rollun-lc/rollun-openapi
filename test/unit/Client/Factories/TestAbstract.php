<?php


namespace rollun\test\OpenAPI\unit\Client\Factories;


use PHPUnit\Framework\TestCase;
use Zend\ServiceManager\ServiceManager;

abstract class TestAbstract extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $container;

    public function setUp()
    {
        global $container;
        $this->container = clone $container;
    }

    protected function configureContainer($newConfig = [])
    {
        $config = $this->container->get('config');
        $config = $this->mergeArray($config, $newConfig);
        $serviceManager = new ServiceManager();
        $serviceManager->setService('config', $config);
        $serviceManager->configure($config['dependencies']);
        $this->container = $serviceManager;
    }

    protected function setServiceToContainer($name, $service)
    {
        $this->container->setService($name, $service);
    }

    private function mergeArray(array $a, array $b)
    {
        foreach ($b as $key => $value) {
            if (isset($a[$key]) || array_key_exists($key, $a)) {
                if (is_int($key)) {
                    $a[] = $value;
                } elseif (is_array($value) && is_array($a[$key])) {
                    $a[$key] = $this->mergeArray($a[$key], $value);
                } else {
                    $a[$key] = $value;
                }
            } else {
                $a[$key] = $value;
            }
        }
        return $a;
    }

    protected function getProperty($instance, $property)
    {
        $array = explode('.', $property, 2);
        if (count($array) > 1) {
            $instance = $this->getProperty($instance, $array[0]);
            return $this->getProperty($instance, $array[1]);
        }

        $reflectionProperty = new \ReflectionProperty($instance, $property);
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($instance);
    }
}