<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

/**
 * Trait NoGet
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoGet
{
    /**
     * @param null $queryData
     *
     * @return mixed
     * @throws \Exception
     */
    public function get($queryData = null)
    {
        throw new \Exception('Not implemented method');
    }
}
