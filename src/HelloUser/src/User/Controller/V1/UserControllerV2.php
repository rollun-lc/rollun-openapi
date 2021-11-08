<?php

declare(strict_types=1);

namespace HelloUser\User\Controller\V1;

use HelloUser\OpenAPI\V1\DTO\User;
use HelloUser\OpenAPI\V1\DTO\UserGETQueryData;
use HelloUser\OpenAPI\V1\DTO\UserListResult;
use HelloUser\OpenAPI\V1\DTO\UserResult;
use HelloUser\OpenAPI\V1\Server\Rest\UserInterface;
use HelloUser\User\Repository\FileRepository;

class UserControllerV2 implements UserInterface
{
    /**
     * @var FileRepository
     */
    private $userRepository;

    public function __construct(FileRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function post(User $bodyData): UserResult
    {
        $user = new \HelloUser\User\Struct\User($bodyData->id, $bodyData->name);
        $this->userRepository->save($user);
        return $this->getById($bodyData->id);
    }

    public function getById(string $id): UserResult
    {
        $user = $this->userRepository->getById($id);
        return $this->makeResult($user);
    }

    private function makeResult(\HelloUser\User\Struct\User $user): UserResult
    {
        $result = new UserResult();
        $result->data = $this->transferToResource($user);
        return $result;
    }

    private function transferToResource(\HelloUser\User\Struct\User $object)
    {
        $user = new User();
        $user->id = $object->getId();
        $user->name = $object->getName();
        return $user;
    }

    public function get(UserGETQueryData $queryData): UserListResult
    {
        // Как проверить фильтр name равен null или не задан?
        $name = $queryData->name;
    }
}