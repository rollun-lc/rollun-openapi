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
}