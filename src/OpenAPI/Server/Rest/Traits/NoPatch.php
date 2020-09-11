<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

/**
 * Trait NoPatch
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoPatch
{
    /**
     * @param $queryData
     * @param $bodyData
     *
     * @return mixed
     * @throws \Exception
     */
    public function patch($queryData, $bodyData)
    {
        throw new \Exception('Not implemented method');
    }
}
