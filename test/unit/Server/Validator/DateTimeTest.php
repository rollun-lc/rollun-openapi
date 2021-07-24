<?php

namespace rollun\test\OpenAPI\unit\Server\Validator;

use OpenAPI\Server\Validator\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    /**
     * @return array<string>
     */
    public function dateTimeGreenDataProvider() : array
    {
        return [
            ['1985-04-12T23:20:50.52Z'],
            ['1937-01-01 12:00:27Z'],
            ['1937-01-01 12:00:27+00:20'],
            ['1937-01-01 12:00:27.666666+00:20'],
            ['1937-01-01T12:00:27.87+00:20'],
            ['1996-12-19T16:39:57-08:00'],
            ['2020-12-23T00:00:00Z'],
            ['2021-04-21T13:46:38.752+00:00']
        ];
    }

    /**
     * @dataProvider dateTimeGreenDataProvider
     */
    public function testGreenDateTimeTypeFormat(string $dateTime): void
    {
        $validator = new DateTime(['format' => DateTime::RFC3339]);
        self::assertTrue($validator->isValid($dateTime));
        self::assertEmpty($validator->getMessages());
    }

    /**
     * @return array<string>
     */
    public function dateTimeRedDataProvider() : array
    {
        return [
            // Wrong formats
            ['1985-04-12'],
            ['1985-04-12 23:12:12'],
            ['1985-04-12T23:12'],
            ['1985-04-12T23:20:50.52'],
            [''],
            ['somestring'],

            // Wrong dates
            ['1990-12-31T23:59:60Z'],
            ['1990-12-31T15:59:60-08:00'],
        ];
    }

    /**
     * @dataProvider dateTimeRedDataProvider
     */
    public function testRedDateTimeTypeFormat(string $dateTime) : void
    {
        $validator = new DateTime(['format' => DateTime::RFC3339]);
        self::assertFalse($validator->isValid($dateTime));
        self::assertNotEmpty($validator->getMessages());
    }
}