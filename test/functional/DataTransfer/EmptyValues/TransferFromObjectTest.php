<?php

declare(strict_types=1);

namespace rollun\test\OpenAPI\functional\DataTransfer\EmptyValues;

use Articus\DataTransfer\Service;
use rollun\test\OpenAPI\functional\FunctionalTestCase;

class TransferFromObjectTest extends FunctionalTestCase
{
    public function testHasUserName()
    {
        $user = new User();
        $user->id = uniqid();
        $user->snakeCase = uniqid();
        $user->camelCase = uniqid();

        $data = $this->transferFromObject($user);

        self::assertTrue(isset($data['id']));
        self::assertEquals($user->id, $data['id']);

        self::assertTrue(isset($data['snake_case']));
        self::assertEquals($user->snakeCase, $data['snake_case']);

        self::assertTrue(isset($data['camelCase']));
        self::assertEquals($user->camelCase, $data['camelCase']);
    }

    public function testHasNotUserName()
    {
        $user = new User();
        $user->id = uniqid();

        $data = $this->transferFromObject($user);

        self::assertTrue(isset($data['id']));
        self::assertEquals($user->id, $data['id']);

        self::assertFalse(array_key_exists('snake_case', $data));
        self::assertFalse(array_key_exists('camelCase', $data));
    }

    private function transferFromObject(object $user): array
    {
        return $this->getDataTransfer()->extractFromTypedData($user);
    }

    private function getDataTransfer(): Service
    {
        return $this->getContainer()->get(Service::class);
    }
}
