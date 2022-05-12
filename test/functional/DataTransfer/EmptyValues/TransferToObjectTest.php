<?php

declare(strict_types=1);

namespace rollun\test\OpenAPI\functional\DataTransfer\EmptyValues;

use Articus\DataTransfer\Service;
use rollun\test\OpenAPI\functional\FunctionalTestCase;

class TransferToObjectTest extends FunctionalTestCase
{
    public function testHasRequiredFields()
    {
        $user = $this->transferToObject([
            'id' => $id = uniqid(),
            'snake_case' => $snakeCase = uniqid(),
            'camelCase' => $camelCase = uniqid()
        ]);

        self::assertTrue($user->hasId());
        self::assertEquals($id, $user->id);

        self::assertTrue($user->hasSnakeCase());
        self::assertEquals($snakeCase, $user->snakeCase);

        self::assertTrue($user->hasCamelCase());
        self::assertEquals($camelCase, $user->camelCase);
    }

    public function testHasNotUserName()
    {
        $user = $this->transferToObject([
            'id' => $id = uniqid(),
        ]);

        self::assertTrue($user->hasId());
        self::assertEquals($id, $user->id);

        self::assertFalse($user->hasSnakeCase());
        self::assertNull($user->snakeCase);

        self::assertFalse($user->hasCamelCase());
        self::assertNull($user->camelCase);
    }

    private function transferToObject(array $data): User
    {
        $user = new User();
        $this->getDataTransfer()->transferToTypedData($data, $user);
        return $user;
    }

    private function getDataTransfer(): Service
    {
        return $this->getContainer()->get(Service::class);
    }
}
