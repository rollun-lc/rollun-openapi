<?php

declare(strict_types=1);

namespace HelloUser\User\Controller\V1;

use rollun\utils\Json\Exception;

class UserController
{
    public function post($bodyData)
    {
        return [
            'data' => $bodyData
        ];
    }
}
