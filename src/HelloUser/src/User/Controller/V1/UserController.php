<?php

declare(strict_types=1);

namespace HelloUser\User\Controller\V1;

class UserController
{
    public function post($bodyData)
    {
        return [
            'data' => $bodyData
        ];
    }
}
