<?php

namespace rollun\test\OpenAPI\functional;

use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use rollun\datastore\DataStore\Interfaces\DataStoreInterface;
use rollun\dic\InsideConstruct;
use Xiag\Rql\Parser\Query;

/**
 * У Функциональных тестов APP_ENV = 'test' согласно phpunit.xml
 *
 * @package rollun\test\Functional
 */
class FunctionalTestCase extends PHPUnitTestCase
{
    /**
     * @var ServiceManager|null
     */
    private $container = null;

    protected function getContainer(): ServiceManager
    {
        if ($this->container === null) {
            $this->container = require __DIR__ . '/../../config/container.php';
            InsideConstruct::setContainer($this->container);
        }

        return $this->container;
    }
}
