<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

use rollun\Callables\Task\ResultInterface;

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
     * @return ResultInterface
     * @throws \Exception
     */
    public function patchById($id, $bodyData): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
