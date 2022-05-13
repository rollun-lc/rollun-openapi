<?php

declare(strict_types=1);

use HelloUser\User\Controller\V1\UserController;

return [
  'dependencies' => [
      'aliases' => [
          'User1Controller' => UserController::class
      ],
      'invokables' => [
          UserController::class
      ]
  ]
];
