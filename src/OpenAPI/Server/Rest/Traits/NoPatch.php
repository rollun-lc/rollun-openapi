<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

use rollun\Callables\Task\ResultInterface;

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
     * @return ResultInterface
     * @throws \Exception
     */
    public function patch($queryData, $bodyData): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
