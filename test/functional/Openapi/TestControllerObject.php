<?php


namespace rollun\test\OpenAPI\functional\Openapi;


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
        $limit = $query['id'] ? count($query['id']) : 1;
        for ($i = 1; $i <= $limit; $i++) {
            $data[] = [
                'id' => (string) $i,
                'name' => $query['name'] ?? uniqid('', false)
            ];
        }
        return [
            'data' => $data
        ];
    }

    public function testPathParamCustomGet($pathParam, $queryParams)
    {
        return [
            'data' => [
                'pathParam' => $pathParam,
                'queryParam' => $queryParams->queryParam
            ]
        ];
    }

    public function testPathParamCustomPost($pathParam, $bodyParams)
    {
        if (empty($pathParam)) {
            throw new \Exception('pathParam is required');
        }
        return $bodyParams;
    }

    public function customOperationGet($pathParam, $queryParams)
    {
        return [
            'data' => [
                'pathParam' => $pathParam,
                'queryParam' => $queryParams->queryParam
            ]
        ];
    }

    public function customOperationPost($pathParam, $bodyParams)
    {
        if (empty($pathParam)) {
            throw new \Exception('pathParam is required');
        }
        return $bodyParams;
    }
}