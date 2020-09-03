<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

use rollun\Callables\Task\ResultInterface;

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
     * @return ResultInterface
     * @throws \Exception
     */
    public function putById($id, $bodyData): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
