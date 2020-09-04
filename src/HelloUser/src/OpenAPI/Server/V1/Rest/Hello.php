<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\Server\V1\Rest;

use OpenAPI\Server\Rest\BaseAbstract;
use rollun\Callables\Task\Result;
use rollun\Callables\Task\ResultInterface;
use rollun\dic\InsideConstruct;

/**
 * Class Hello
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class Hello extends BaseAbstract
{
    /**
     * @var User
     */
    protected $userObject;

    public function __construct(User $userObject = null)
    {
        InsideConstruct::init(['userObject' => User::class]);
    }

    /**
     * @inheritDoc
     */
    public function getById($id): ResultInterface
    {
        // get user
        $user = $this->userObject->getById($id)->toArrayForDto();

        // create hello object
        $data = new \HelloUser\OpenAPI\Server\V1\DTO\Hello();
        $data->message = "Hello, {$user['data']['name']}!";

        return new Result($data);
    }
}
