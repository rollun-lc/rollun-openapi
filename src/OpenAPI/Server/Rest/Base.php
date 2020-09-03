<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest;

/**
 * Class Base
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Base implements RestInterface
{
    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function post($bodyData): array
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function patch($queryData, $bodyData): array
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function delete($queryData): array
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function get($queryData): array
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function putById($id, $bodyData): array
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function patchById($id, $bodyData): array
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function deleteById($id): array
    {
        throw new \Exception('Not implemented method');
    }

    /**
     * @inheritDoc
     * @throws \Exception
     */
    public function getById($id): array
    {
        throw new \Exception('Not implemented method');
    }
}
