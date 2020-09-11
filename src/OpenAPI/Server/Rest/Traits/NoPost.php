<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest\Traits;

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
     * @return mixed
     * @throws \Exception
     */
    public function post($bodyData)
    {
        throw new \Exception('Not implemented method');
    }
}
