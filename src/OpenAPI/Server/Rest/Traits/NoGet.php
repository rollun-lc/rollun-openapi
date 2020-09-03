<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

use rollun\Callables\Task\ResultInterface;

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
     * @return ResultInterface
     * @throws \Exception
     */
    public function get($queryData = null): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
