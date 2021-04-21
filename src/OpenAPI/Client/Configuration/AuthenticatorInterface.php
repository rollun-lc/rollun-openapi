<?php


namespace OpenAPI\Client\Configuration;


interface AuthenticatorInterface
{
    /**
     * @return string
     */
    public function getAccessToken(): string;
}