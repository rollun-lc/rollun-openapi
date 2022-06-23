<?php


namespace rollun\test\OpenAPI\functional\Openapi;


use Test\OpenAPI\V1_0_1\DTO\Bla;
use Test\OpenAPI\V1_0_1\DTO\BlaResult;

class BlaControllerObject
{
    public function post($bodyDataArray = null)
    {
        return [];
    }

    public function get($query)
    {
        if (isset($query['name']) && $query['name'] === 'Exception') {
            throw new \Exception('Test exception');
        }

        for ($i = 0; $i < 3; $i++) {
            $bla = new Bla();
            $bla->id = (string) ($i + 1);
            if (empty($query['name'])) {
                $bla->name = uniqid();
            } elseif ($query['name'] !== 'Error') {
                $bla->name = 'Bla' . ($i + 1);
            }
            $items[] = $bla;
        }

        if (isset($query['id'])) {
            $ids = explode(',', $query['id']);
            $items = array_values(
                array_filter($items, function ($item) use ($ids) {
                    return in_array($item->id, $ids);
                })
            );
        }

        $collection = new BlaResult();
        $collection->data = $items;

        return $collection;
    }
}
