<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest;

use rollun\Callables\Task\ResultInterface;

/**
 * Interface RestInterface
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
interface RestInterface
{
    /**
     * @param mixed $bodyData
     *
     * @return ResultInterface
     */
    public function post($bodyData): ResultInterface;

    /**
     * @param mixed $queryData
     * @param mixed $bodyData
     *
     * @return ResultInterface
     */
    public function patch($queryData, $bodyData): ResultInterface;

    /**
     * @param mixed $queryData
     *
     * @return ResultInterface
     */
    public function delete($queryData = null): ResultInterface;

    /**
     * @param mixed $queryData
     *
     * @return ResultInterface
     */
    public function get($queryData = null): ResultInterface;

    /**
     * @param mixed $id
     * @param mixed $bodyData
     *
     * @return ResultInterface
     */
    public function putById($id, $bodyData): ResultInterface;

    /**
     * @param mixed $id
     * @param mixed $bodyData
     *
     * @return ResultInterface
     */
    public function patchById($id, $bodyData): ResultInterface;

    /**
     * @param mixed $id
     *
     * @return ResultInterface
     */
    public function deleteById($id): ResultInterface;

    /**
     * @param mixed $id
     *
     * @return ResultInterface
     */
    public function getById($id): ResultInterface;
}
