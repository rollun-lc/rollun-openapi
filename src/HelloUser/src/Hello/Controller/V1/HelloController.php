<?php

declare(strict_types=1);

namespace HelloUser\Hello\Controller\V1;

use Articus\DataTransfer\Service as DataTransferService;
use HelloUser\OpenAPI\V1\DTO\HelloResult;
use HelloUser\OpenAPI\V1\Server\Rest\HelloInterface;
use HelloUser\User\Repository\FileRepository;

class HelloController implements HelloInterface
{
    /**
     * @var FileRepository
     */
    private $userRepository;

    /**
     * @var DataTransferService
     */
    private $dataTransfer;

    /**
     * Hello constructor.
     *
     * @param FileRepository $userRepository
     * @param DataTransferService $dataTransfer
     */
    public function __construct(FileRepository $userRepository, DataTransferService $dataTransfer)
    {
        $this->userRepository = $userRepository;
        $this->dataTransfer = $dataTransfer;
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): HelloResult
    {
        // get user
        $user = $this->userRepository->getById($id);

        return $this->transfer([
            'data' => [
                'message' => "Hello, {$user->getName()}!"
            ]
        ]);
    }

    private function transfer(array $array): HelloResult
    {
        $result = new HelloResult();
        $this->dataTransfer->transferToTypedData($array, $result);
        return $result;
    }
}