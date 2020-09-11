<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

/**
 * Trait NoDeleteById
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoDeleteById
{
    /**
     * @param $id
     *
     * @return mixed
     * @throws \Exception
     */
    public function deleteById($id)
    {
        throw new \Exception('Not implemented method');
    }
}
