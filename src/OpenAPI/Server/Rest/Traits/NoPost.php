<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

use rollun\Callables\Task\ResultInterface;

/**
 * Trait NoPost
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
trait NoPost
{
    /**
     * @param $bodyData
     *
     * @return ResultInterface
     * @throws \Exception
     */
    public function post($bodyData): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
