<?php
declare(strict_types=1);

namespace HelloUser\OpenAPI\Server\V1\Rest;

use OpenAPI\Server\Rest\BaseAbstract;
use rollun\Callables\Task\Result;
use rollun\Callables\Task\ResultInterface;

/**
 * Class User
 *
 * @author r.ratsun <r.ratsun.rollun@gmail.com>
 */
class User extends BaseAbstract
{
    const DIR = 'data/examples/user';

    /**
     * @inheritDoc
     */
    public function post($bodyData): ResultInterface
    {
        if (!file_exists(self::DIR)) {
            mkdir(self::DIR, 0777, true);
            sleep(1);
        }

        // prepare fileName
        $fileName = $this->getFilePath($bodyData->id);

        if (file_exists($fileName)) {
            throw new \InvalidArgumentException('Such user already exists');
        }

        // save data to file
        file_put_contents($fileName, json_encode(['id' => $bodyData->id, 'name' => $bodyData->name]));
        sleep(1);

        return $this->getById($bodyData->id);
    }

    /**
     * @inheritDoc
     */
    public function getById($id): ResultInterface
    {
        // prepare fileName
        $fileName = $this->getFilePath($id);

        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException('No such user');
        }

        // get data from file
        $data = json_decode(file_get_contents($fileName), true);

        // create user object
        $user = new \HelloUser\OpenAPI\Server\V1\DTO\User();
        $user->id = $data['id'];
        $user->name = $data['name'];

        return new Result($user);
    }

    /**
     * @param $id
     *
     * @return string
     */
    protected function getFilePath($id): string
    {
        return self::DIR . '/' . $id . '.json';
    }
}
