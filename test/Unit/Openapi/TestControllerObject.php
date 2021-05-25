<?php


namespace rollun\test\OpenAPI\Unit\Openapi;


class TestControllerObject
{
    public function post($bodyDataArray)
    {
        return $bodyDataArray;
    }

    public function getById($id)
    {
        return [
            'id' => $id,
            'name' => 'Test',
        ];
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