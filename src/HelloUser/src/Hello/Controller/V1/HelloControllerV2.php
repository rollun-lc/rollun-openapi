<?php

declare(strict_types=1);

namespace HelloUser\Hello\Controller\V1;

use HelloUser\OpenAPI\V1\DTO\HelloResult;
use HelloUser\User\Repository\FileRepository;

class HelloControllerV2
{
    /**
     * @var FileRepository|null
     */
    private $userRepository;

    public function __construct(FileRepository $userRepository = null)
    {
        $this->userRepository = $userRepository;
    }

    public function getById(string $id): HelloResult
    {
        $user = $this->userRepository->getById($id);

        $result = new HelloResult();
        $result->data = "Hello, {$user->getName()}";
        return $result;
    }
}