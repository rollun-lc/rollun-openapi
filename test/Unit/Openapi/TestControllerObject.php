<?php


namespace rollun\test\OpenAPI\Unit\Openapi;


use Test\OpenAPI\V1_0_1\DTO\Test;

class TestControllerObject
{
    public function post($bodyDataArray)
    {
        return $bodyDataArray;
    }

    public function getById($id)
    {
        $test = new Test();
        $test->id = $id;
        $test->name = 'Test';
        return $test;
    }

    public function get($query)
    {
        return [
            'data' => [
                [
                    'id' => '1',
                    'name' => $query['name']
                ]
            ]
        ];
    }
}