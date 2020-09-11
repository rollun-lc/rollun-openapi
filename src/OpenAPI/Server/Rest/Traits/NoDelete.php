<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

/**
 * Trait NoDelete
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoDelete
{
    /**
     * @param $queryData
     *
     * @return mixed
     * @throws \Exception
     */
    public function delete($queryData = null)
    {
        throw new \Exception('Not implemented method');
    }
}
