<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

use rollun\Callables\Task\ResultInterface;

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
     * @return ResultInterface
     * @throws \Exception
     */
    public function delete($queryData = null): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
