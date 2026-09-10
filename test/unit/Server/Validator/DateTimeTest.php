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
     * Since PHP 8.2.0 \DateTime::getLastErrors() returns false instead of an array with zero
     * counters when the last parsing was clean, so reading it as an array raises
     * "Trying to access array offset on false" for every valid value.
     *
     * @dataProvider dateTimeGreenDataProvider
     */
    public function testGreenDateTimeTypeFormatRaisesNoPhpWarning(string $dateTime): void
    {
        $raised = [];
        set_error_handler(
            static function (int $errno, string $errstr) use (&$raised): bool {
                $raised[] = $errstr;

                return true;
            },
            E_WARNING | E_NOTICE
        );

        try {
            $validator = new DateTime(['format' => DateTime::RFC3339]);
            $isValid = $validator->isValid($dateTime);
        } finally {
            restore_error_handler();
        }

        self::assertTrue($isValid);
        self::assertSame([], $raised);
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