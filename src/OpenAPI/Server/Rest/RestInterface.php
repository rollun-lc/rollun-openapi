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
     * @return mixed
     */
    public function post($bodyData);

    /**
     * @param mixed $queryData
     * @param mixed $bodyData
     *
     * @return mixed
     */
    public function patch($queryData, $bodyData);

    /**
     * @param mixed $queryData
     *
     * @return mixed
     */
    public function delete($queryData = null);

    /**
     * @param mixed $queryData
     *
     * @return mixed
     */
    public function get($queryData = null);

    /**
     * @param mixed $id
     * @param mixed $bodyData
     *
     * @return mixed
     */
    public function putById($id, $bodyData);

    /**
     * @param mixed $id
     * @param mixed $bodyData
     *
     * @return mixed
     */
    public function patchById($id, $bodyData);

    /**
     * @param mixed $id
     *
     * @return mixed
     */
    public function deleteById($id);

    /**
     * @param mixed $id
     *
     * @return mixed
     */
    public function getById($id);
}
