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
        self::assertTrue(isset($user->id));
        self::assertNotEmpty($user->id);

        self::assertTrue($user->hasSnakeCase());
        self::assertEquals($snakeCase, $user->snakeCase);
        self::assertTrue(isset($user->snakeCase));
        self::assertNotEmpty($user->snakeCase);

        self::assertTrue($user->hasCamelCase());
        self::assertEquals($camelCase, $user->camelCase);
        self::assertTrue(isset($user->camelCase));
        self::assertNotEmpty($user->camelCase);
    }

    public function testHasNotUserName()
    {
        $user = $this->transferToObject([
            'id' => $id = uniqid(),
        ]);

        self::assertTrue($user->hasId());
        self::assertEquals($id, $user->id);
        self::assertTrue(isset($user->id));
        self::assertNotEmpty($user->id);

        self::assertFalse($user->hasSnakeCase());
        self::assertNull($user->snakeCase);
        self::assertFalse(isset($user->snakeCase));
        self::assertEmpty($user->snakeCase);

        self::assertFalse($user->hasCamelCase());
        self::assertNull($user->camelCase);
        self::assertFalse(isset($user->camelCase));
        self::assertEmpty($user->camelCase);
    }

    public function unsetDataProvider(): array
    {
        return [
            'Unset initialized' => [
                [
                    'id' => $id = uniqid(),
                ],
                'id'
            ],
            'Unset not initialized' => [
                [
                    'id' => $id = uniqid(),
                ],
                'camelCase'
            ]
        ];
    }

    /**
     * @dataProvider unsetDataProvider
     */
    public function testUnset(array $data, string $field)
    {
        $user = $this->transferToObject($data);

        unset($user->{$field});
        self::assertFalse(isset($user->{$field}));
    }

    public function testIterable(): void
    {
        $user = $this->transferToObject([
            'id' => $id = uniqid(),
            'snake_case' => $snakeCase = uniqid()
        ]);

        $result = [];
        foreach ($user as $key => $value) {
            $result[$key] = $value;
        }

        self::assertEquals([
            'id' => $id,
            'snakeCase' => $snakeCase
        ], $result);
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