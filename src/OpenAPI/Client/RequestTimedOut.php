<?php

namespace OpenAPI\Client;

/**
 * Do not receive response after a while, or receive HTTP 504 | 524 status code
 */
class RequestTimedOut extends ApiException
{
}