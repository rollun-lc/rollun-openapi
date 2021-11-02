<?php

declare(strict_types=1);

namespace HelloUser\User\Repository;

use HelloUser\User\Struct\User;

class FileRepository
{
    private const DIR = 'data/examples/user';

    public function save(User $user): void
    {
        $this->createDirectoryIfNotExist(self::DIR);
        $fileName = $this->makeFileName($user->getId());
        $this->ensureUserFileNotExist($fileName);
        $this->saveToFile($user, $fileName);
    }

    public function getById(string $id): User
    {
        $fileName = $this->makeFileName($id);
        if (!file_exists($fileName)) {
            throw new \InvalidArgumentException('No such user');
        }

        $user = json_decode(file_get_contents($fileName), true);
        return new User($user['id'], $user['name']);
    }

    private function createDirectoryIfNotExist(string $path)
    {
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
    }

    private function ensureUserFileNotExist(string $fileName)
    {
        if (file_exists($fileName)) {
            throw new \InvalidArgumentException('Such user already exists');
        }
    }

    private function saveToFile(User $user, string $fileName)
    {
        file_put_contents($fileName, json_encode([
            'id' => $user->getId(),
            'name' => $user->getName()
        ]));
    }

    private function makeFileName($id): string
    {
        return self::DIR . '/' . $id . '.json';
    }
}