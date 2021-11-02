<?php

declare(strict_types=1);

namespace HelloUser\Hello\Controller\V1;

use HelloUser\OpenAPI\V1\DTO\HelloResult;
use HelloUser\OpenAPI\V1\Server\Rest\User;
use rollun\dic\InsideConstruct;

class HelloControllerV2
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

    public function getById(string $id): HelloResult
    {
        $user = $this->userObject->getById($id);

        $result = new HelloResult();
        $result->data = "Hello, {$user['data']['name']}";
        return $result;
    }
}