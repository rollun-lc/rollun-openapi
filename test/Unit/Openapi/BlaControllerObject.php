<?php


namespace rollun\test\OpenAPI\Unit\Openapi;


use Test\OpenAPI\V1_0_1\DTO\Bla;
use Test\OpenAPI\V1_0_1\DTO\BlaCollection;

class BlaControllerObject
{
    public function post($bodyDataArray = null)
    {
        return [];
    }

    public function get($query)
    {
        if ($query['name'] === 'Exception') {
            throw new \Exception('Test exception');
        }

        for ($i = 0; $i < 3; $i++) {
            $bla = new Bla();
            $bla->id = uniqid('', false);
            if ($query['name'] === 'OK') {
                $bla->name = 'Bla' . ($i + 1);
            }
            $items[] = $bla;
        }

        $collection = new BlaCollection();
        $collection->data = $items;

        return $collection;
    }
}