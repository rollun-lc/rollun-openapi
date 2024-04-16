<?php

namespace rollun\test\OpenAPI\functional\Client;

use FilesystemIterator;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class ClientTestCase extends TestCase
{
    protected static $container;

    public static function setUpBeforeClass(): void
    {
        global $container;
        self::$container = $container;
    }

    protected static function generateClient(string $manifestPath): void
    {
        $command = 'php bin/openapi-generator generate:client --manifest=' . $manifestPath;
        exec($command);
    }

    protected static function removeGenerated(string $manifestPath): void
    {
        static::removeDirectory(static::getGeneratedPath($manifestPath));
        static::removeFile(static::getServerConfigFilePath($manifestPath));
    }

    protected static function generateServer(string $manifestPath): void
    {
        $command = 'php bin/openapi-generator generate:server --manifest=' . $manifestPath;
        exec($command);
    }

    protected static function getGeneratedPath(string $manifestPath): string
    {
        $manifestTitle = static::getManifestTitle($manifestPath);
        return static::getRootPath() . '/src/' . $manifestTitle . '/src/OpenAPI';
    }

    protected static function getServerConfigFilePath(string $manifestPath): string
    {
        $manifestTitle = static::getManifestTitle($manifestPath);
        $manifestVersion = static::getManifestVersion($manifestPath);
        return static::getRootPath() . '/config/autoload/' . lcfirst($manifestTitle) . '_v' . $manifestVersion . '_path_handler.global.php';
    }

    protected static function getManifestVersion(string $manifestPath): string
    {
        $manifestContent = yaml_parse_file($manifestPath);
        $manifestVersion = $manifestContent['info']['version'] ?? null;
        if (empty($manifestVersion)) {
            throw new \InvalidArgumentException("Openapi manifest ($manifestPath) has no title.");
        }
        return preg_replace("/[^0-9]/", '', $manifestVersion);
    }

    protected static function getManifestTitle(string $manifestPath): string
    {
        $manifestContent = yaml_parse_file($manifestPath);
        $manifestTitle = $manifestContent['info']['title'] ?? null;
        if (empty($manifestTitle)) {
            throw new \InvalidArgumentException("Openapi manifest ($manifestPath) has no title.");
        }
        return preg_replace("/[^a-zA-Z0-9]/", '', $manifestTitle);
    }

    protected static function getRootPath(): string
    {
        return realpath(__DIR__ . '/../../..');
    }

    private static function removeFile(string $path): void
    {
        if(file_exists($path)) {
            unlink($path);
        }
    }

    // https://stackoverflow.com/questions/3349753/delete-directory-with-files-in-it
    private static function removeDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }
        $iterator = new RecursiveDirectoryIterator($path,FilesystemIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($iterator,RecursiveIteratorIterator::CHILD_FIRST);
        foreach($files as $file) {
            if ($file->isDir()){
                rmdir($file->getPathname());
            } else {
                unlink($file->getPathname());
            }
        }
        rmdir($path);
    }
}