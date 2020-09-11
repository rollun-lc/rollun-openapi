<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

/**
 * Trait NoPatchById
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoPatchById
{
    /**
     * @param $id
     * @param $bodyData
     *
     * @return mixed
     * @throws \Exception
     */
    public function patchById($id, $bodyData)
    {
        throw new \Exception('Not implemented method');
    }
}
