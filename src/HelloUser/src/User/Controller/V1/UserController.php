<?php

declare(strict_types=1);

namespace HelloUser\User\Controller\V1;

use Articus\DataTransfer\Service as DataTransferService;
use HelloUser\OpenAPI\V1\DTO\User;
use HelloUser\OpenAPI\V1\DTO\UserResult;
use HelloUser\OpenAPI\V1\UserInterface;
use HelloUser\User\Repository\FileRepository;

class UserController implements UserInterface
{
    /**
     * @var FileRepository
     */
    private $userRepository;

    /**
     * @var DataTransferService
     */
    private $dataTransfer;

    public function __construct(
        FileRepository $userRepository,
        DataTransferService $dataTransfer
    )
    {
        $this->userRepository = $userRepository;
        $this->dataTransfer = $dataTransfer;
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
        return $this->transfer([
            'data' => [
                'id' => $user->getId(),
                'name' => $user->getName()
            ]
        ]);
    }

    private function transfer(array $array): UserResult
    {
        $result = new UserResult();
        $this->dataTransfer->transferToTypedData($array, $result);
        return $result;
    }
}