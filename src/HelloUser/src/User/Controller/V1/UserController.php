<?php

declare(strict_types=1);

namespace HelloUser\User\Controller\V1;

class UserController
{
    const DIR = 'data/examples/user';

    /**
     * @inheritDoc
     */
    public function post($bodyData)
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
    public function getById($id)
    {
        // prepare fileName
        $fileName = $this->getFilePath($id);

        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException('No such user');
        }

        return [
            'data' => json_decode(file_get_contents($fileName), true)
        ];
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