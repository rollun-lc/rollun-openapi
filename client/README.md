# OpenAPI client generator bridge

Библиотека, которая дает возможность подключить, сгенерированный OpenAPI генератором, клиентский код к вашему проекту. 

###Установка:

1. Установите [openapi-generator](https://openapi-generator.tech/). Для проверки выполните команду:

   ```openapi-generator version```, в случае когда openapi-generator установлен вы увидите версию генератора.
   
2. Установите библиотеку, для этого выполните команду 

   ```composer require rollun-com/rollun-openapi-client-bridge```

3. Подготовьте openapi манифест. Детали [здесь](https://github.com/rollun-com/rollun-openapi-server-bridge/blob/master/docs/manifest.md).
4. Скачайте openapi манифест. Для этого перейдите на https://app.swaggerhub.com/home?type=API, откройте нужный вам манифест и сделайте экспорт в виде yaml файла. При скачивании, рекомендуется называть документ **openapi.yaml** так, как такое имя используется генератором по умолчанию.
   ![alt text](https://github.com/rollun-com/rollun-openapi-server-bridge/blob/master/docs/assets/img/openapi.png)
5. Для генерации кода выполните команду:

   ```php vendor/bin/openapi-client-generate```

6. Обязательно добавьте сгенерированные классы в аутолоадер композера.
   ```
     "autoload": {
       "psr-4": {
         "ModuleName\\": "src/ModuleName/src/"
       }
     },
   ```   

### Использование
Для того чтобы отправить запрос вам достаточно:
```php
$apiInstance = $container->get(\ModuleName\OpenAPI\Client\V1\Api\FooApi::class); // создаем $apiInstance

$result = $apiInstance->getUserById('qwerty123'); // Вызываем отправку запроса. У каждого $apiInstance есть специальные методы, которые были сгенерированы по манифесту.
```
