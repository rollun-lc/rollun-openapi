<?php

declare(strict_types=1);

namespace HelloUser\Hello\Controller\V1;

use Articus\DataTransfer\Service as DataTransferService;
use HelloUser\OpenAPI\V1\DTO\HelloResult;
use HelloUser\OpenAPI\V1\Server\Rest\HelloInterface;
use HelloUser\OpenAPI\V1\Server\Rest\User;
use rollun\dic\InsideConstruct;

class HelloController implements HelloInterface
{
    /**
     * @var User
     */
    protected $userObject;

    /**
     * @var DataTransferService
     */
    private $dataTransfer;

    /**
     * Hello constructor.
     *
     * @param User|null $userObject
     *
     * @throws \ReflectionException
     */
    public function __construct(User $userObject = null, DataTransferService $dataTransfer = null)
    {
        InsideConstruct::init(['userObject' => User::class, 'dataTransfer' => DataTransferService::class]);
    }

    /**
     * @inheritDoc
     */
    public function getById(string $id): HelloResult
    {
        // get user
        $user = $this->userObject->getById($id);

        return $this->transfer([
            'data' => [
                'message' => "Hello, {$user['data']['name']}!"
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