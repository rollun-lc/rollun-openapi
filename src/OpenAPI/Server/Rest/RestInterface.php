<?php
declare(strict_types=1);

namespace OpenAPI\Server\Rest;

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
     * @return array
     */
    public function post($bodyData): array;

    /**
     * @param mixed $queryData
     * @param mixed $bodyData
     *
     * @return array
     */
    public function patch($queryData, $bodyData): array;

    /**
     * @param mixed $queryData
     *
     * @return array
     */
    public function delete($queryData): array;

    /**
     * @param mixed $queryData
     *
     * @return array
     */
    public function get($queryData): array;

    /**
     * @param mixed $id
     * @param mixed $bodyData
     *
     * @return array
     */
    public function putById($id, $bodyData): array;

    /**
     * @param mixed $id
     * @param mixed $bodyData
     *
     * @return array
     */
    public function patchById($id, $bodyData): array;

    /**
     * @param mixed $id
     *
     * @return array
     */
    public function deleteById($id): array;

    /**
     * @param mixed $id
     *
     * @return array
     */
    public function getById($id): array;
}
