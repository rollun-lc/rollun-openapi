<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

use rollun\Callables\Task\ResultInterface;

/**
 * Trait NoGetById
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoGetById
{
    /**
     * @param $id
     *
     * @return ResultInterface
     * @throws \Exception
     */
    public function getById($id): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
