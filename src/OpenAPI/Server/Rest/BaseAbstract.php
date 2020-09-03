<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest;

use rollun\Callables\Task\ResultInterface;

/**
 * Abstract class BaseAbstract
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
abstract class BaseAbstract implements RestInterface
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function post($bodyData): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function patch($queryData, $bodyData): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function delete($queryData = null): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function get($queryData = null): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function putById($id, $bodyData): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function patchById($id, $bodyData): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function deleteById($id): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function getById($id): ResultInterface
    {
        throw new \Exception('Not implemented method');
    }
}
