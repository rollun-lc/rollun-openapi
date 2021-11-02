<?php

declare(strict_types=1);

namespace HelloUser\Hello\Controller\V1;

use HelloUser\OpenAPI\V1\Server\Rest\User;
use rollun\dic\InsideConstruct;

class HelloController
{
    /**
     * @var User
     */
    protected $userObject;

    /**
     * Hello constructor.
     *
     * @param User|null $userObject
     *
     * @throws \ReflectionException
     */
    public function __construct(User $userObject = null)
    {
        InsideConstruct::init(['userObject' => User::class]);
    }

    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        // get user
        $user = $this->userObject->getById($id);

        return [
            'data' => [
                'message' => "Hello, {$user['data']['name']}!"
            ]
        ];
    }
}