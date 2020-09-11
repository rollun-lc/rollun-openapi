<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

/**
 * Trait NoGetById
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoGetById
{
    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function getById($id)
    {
        throw new \Exception('Not implemented method');
    }
}
