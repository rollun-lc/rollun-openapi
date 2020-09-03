<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

use rollun\Callables\Task\ResultInterface;

/**
 * Trait NoDeleteById
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoDeleteById
{
    /**
     * @param $id
     *
     * @return ResultInterface
     * @throws \Exception
     */
    public function deleteById($id): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
