<?php


namespace OpenAPI\Client\Api;


use InvalidArgumentException;

interface ApiInterface
{
    /**
     * Sets the index of the host to which requests will be sent.
     * To see all the hosts, you can run getHosts()
     *
     * @param int $hostIndex
     * @throws InvalidArgumentException if no host is found with this index
     */
    public function setHostIndex(int $hostIndex): void;

    /**
     * Returns an array of host settings
     * example:
     * [
     *     [
     *         "url" => "http://rollun-openapi/openapi/HelloUser/v1",
     *         "description" => "No description provided",
     *     ],
     *     [
     *         "url" => "http://localhost:8080/openapi/HelloUser/v1",
     *         "description" => "No description provided",
     *     ],
     * ]
     *
     * @return array
     */
    public function getHosts(): array;
}