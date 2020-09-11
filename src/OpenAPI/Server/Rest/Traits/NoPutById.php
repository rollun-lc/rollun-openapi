<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

/**
 * Trait NoPutById
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoPutById
{
    /**
     * @param $id
     * @param $bodyData
     *
     * @return mixed
     * @throws \Exception
     */
    public function putById($id, $bodyData)
    {
        throw new \Exception('Not implemented method');
    }
}
