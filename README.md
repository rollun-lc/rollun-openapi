# OpenAPI generator

Библиотека, которая дает возможность подключить, сгенерированный OpenAPI генератором, серверный или клиентский код к вашему проекту.

## Установка:
1. Установите [openapi-generator](https://openapi-generator.tech/) ниже 5й (не включительно). Для проверки выполните команду:

   ```openapi-generator version```, в случае когда openapi-generator установлен вы увидите версию генератора.

   **ВЕРСИЯ ГЕНЕРАТОРА ДОЛЖНА БЫТЬ НИЖЕ ПЯТОЙ.** Связанно это с тем что в 5й версии [убрали](https://github.com/OpenAPITools/openapi-generator/pull/8145/commits) 
   генератор которым мы пользуемся, ему изменили имя и переделали для Laminas вместо Zend.
   

2. Установите библиотеку, для этого выполните команду 

   ```composer require rollun-com/rollun-openapi```
   * **!!!ВАЖНО!!!** После того как композер отработает, проверьте чтобы в файле `/config/config.php` конфиг провайдер `\OpenAPI\ConfigProvider::class` загружался после `\Zend\Expressive\Router\FastRouteRouter\ConfigProvider::class` в ином случаем работать не будет.
 
  
3. Проверить что в контейнере есть `rollun\logger\LifeCycleToken`.

   Под этим именем в контейнере должна находиться строка с идентификатором текущего жизненного цикла приложения.

   Рекомендованный способ это установить библиотеку rollun-com/rollun-logger. В комплекте с которой идет LifeCycleToken.
   Почитать о том как установить его в контейнер можно в [документации](https://github.com/rollun-com/rollun-logger/blob/master/docs/index.md#lifecycletoken)
   библиотеки.
 

4. Подготовьте openapi манифест. Детали [здесь](docs/manifest.md).       


5. Скачайте openapi манифест. Для этого перейдите на https://app.swaggerhub.com/home?type=API, откройте нужный вам манифест и сделайте экспорт в виде yaml файла. При скачивании, рекомендуется называть документ **openapi.yaml** так, как такое имя используется генератором по умолчанию.
   ![alt text](docs/assets/img/openapi.png)
   В версии 8+ манифест скачивать не нужно, можно указывать урл.
6. Для генерации кода выполните команду:

   ```php vendor/bin/openapi-server-generate```
   
   или
   
   ```php vendor/bin/openapi-client-generate```

   В версии 8+ существенно переделаны скрипты запуска генерации и запускается так

   ```php vendor/bin/openapi-generator generate:server```
   или
   ```php vendor/bin/openapi-generator generate:client```

   Команда поддерживает параметры. Передаются в виде --name=value.
   На данный момент реализовано указание манифеста (параметр manifest) в виде пути или урла.
   Например
   
   ```php vendor/bin/openapi-generator generate:client --manifest=openapy.yaml```
   
   Используется пакет [symfony/console](https://github.com/symfony/console).
   
7. Обязательно добавьте сгенерированные классы в аутолоадер композера.
   ```
     "autoload": {
       "psr-4": {
         "SomeModule\\": "src/SomeModule/src/"
       }
     },
   ```
8. При переходе на версии 9+, нужно перегенерировать всех клиентов
   
## Quick Start видео   
Для просмотра видео перейдите по [ссылке](https://drive.google.com/file/d/1kzuJMICC5P4kxlkRZE5UmDD1PwBFVerp/view?usp=sharing).

## Формат даты и времени
Формат даты и времени, согласно спецификации [OpenApi](https://swagger.io/docs/specification/data-models/data-types/) должен возвращаться
в формате [RFC 3339, section 5.6](https://tools.ietf.org/html/rfc3339#section-5.6). Примеры: "2017-07-21T17:32:28Z", "2020-12-11T15:04:02.255Z".
Важно заметить, что php формат `\DateTime::RFC3339 ('Y-m-d\TH:i:sP')` не в полной степени соответствует настоящему RFC 3339 формату, а именно
в php `\DateTime::RFC3339` не допускается Z в конце строки, а так же нету поддержки необязательных миллисекунд.

**До версии 6.1.0 миллисекунды не поддерживаются, валидация даты времени происходит за форматом `'Y-m-d\TH:i:s\Z'`**. 

С версии 6.1.0 валидатор дописан для полного соответствия спецификации [RFC 3339, section 5.6](https://tools.ietf.org/html/rfc3339#section-5.6). 
Но, обязательно нужно перегенерировать код, чтобы поменялся формат даты в анотациях сгенерированных DTO.

## Помещать ли библиотеку в require-dev секцию?
Нет, почти все классы с этой библиотеки нужны для работы в продакшене: роутинг, сереализация дто и т.д.
Для генерации кода используются только команды из ./bin директории, шаблоны из template, а так же пакет ```nette/php-generator```.
Пока что эти зависимости остаются в пакете и подтягиваются в продакшн.

## Документация по реализации серверной части
[Документация по реализации серверной части](docs/server.md)

## Документация по реализации клиентской части
[Документация по реализации клиентской части](docs/client.md)

## Переключение между хостами
С версии 3.1.0 Rest классы реализуют интерфейс [`OpenAPI\Client\Rest\ClientInterface`](src/OpenAPI/Client/Rest/ClientInterface.php),
который включает в себя интерфейс [`OpenAPI\Client\Rest\HostSelectionInterface`](src/OpenAPI/Client/Rest/HostSelectionInterface.php),
который позволяет переключаться между хостами. 

Чтобы воспользоваться этой возможностью, замените `OpenAPI\Server\Rest\RestInterface` на [`OpenAPI\Client\Rest\ClientInterface`](src/OpenAPI/Client/Rest/ClientInterface.php),
который так же включает в себя RestInterface, так что ничего не сломается.

```php
<?php

namespace OpenAPI;

use HelloUser\OpenAPI\V1\Client\Rest\Hello;
use OpenAPI\Client\Rest\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use rollun\dic\InsideConstruct;use Zend\Diactoros\Response;

class TestHandler implements RequestHandlerInterface
{
    /**
     * @var ClientInterface|null
     */
    private $rest;

    public function __construct(ClientInterface $rest = null)
    {
        InsideConstruct::init(['rest' => Hello::class]);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $this->rest->setHostIndex(1);
        $result = $this->rest->getById('10');
        return new Response\JsonResponse($result);
    }
}
```

## Зависает composer install 
Возможно проблема из-за библиотеки "rollun-com/rollun-callback". Попробуйте убрать ее из composer.json и запустить
установку повторно. Если все прошло успешно, то установите эту библиотеку отдельно через composer require.

## Пользовательские действия и эндпоинты

Добавилась возможность кроме стандартных операций (CRUD), генерировать код для отправки с клиента и обработки сервером пользовательских методов, 
которые будут работать по нужным вам эндпоинтам.

Ранее у нас была возможность генерировать лишь 8 методов и, соответственно, иметь лишь 8 путей, например для какой-то сущности "Order":

| № | PHP method | Http method | Path        | Action                        |
|---|------------|-------------|-------------|-------------------------------|
| 1 | post       | POST        | /order      | Создание                      |
| 2 | patch      | PATCH       | /order      | Создание или замена           |
| 3 | get        | PATCH       | /order      | Получение коллекции           |
| 4 | delete     | PATCH       | /order      | Удаление коллекции(?)         |
| 5 | idGet      | PATCH       | /order/{id} | Получение сущности            |
| 6 | idPatch    | PATCH       | /order/{id} | Частичное обновление сущности |
| 7 | idPut      | PATCH       | /order/{id} | Замена сущности               |
| 8 | idDelete   | PATCH       | /order/{id} | Удаление сущности             |


Сейчас можно генерировать любые другие PHP методы с любыми другими путями.
Например, нужно сгенерировать метод, который будет обрабатывать POST запрос по пути /order/{id}/user.
Для этого, в первую очередь, необходимо добавить нужный путь в манифесте.
Далее есть 2 варианта, как привязать этот путь к вашему PHP методу.

#### Вариант 1. Вы можете ничего более (кроме пути) не указывать

```yaml
paths:
  /order/{id}/user:
    post:
      tags:
        - Order
      parameters:
        - name: id
          in: path
          schema:
            type: string
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/User'
```

В этом случае будет сгенерирован метод OrderIdUserPost. То есть методы в классах (Handler, Rest, Api) будут генерироваться автоматически согласно путям в camelCase.
В контроллере необходимо описать метод с таким же названием.
Этот метод будет принимать 2 параметра - $id и $bodyData, то есть в контроллер должен выглядеть примерно так:

```php
public function orderIdUserGet(string $id, User $bodyData)
{
    // code
}
```

#### Вариант 2. Указать в манифесте operationId.
Если вы не хотите, чтобы методы генерировались таким образом, т.е. если методу нужно задать какое-то свое логически понятное имя,
в манифесте можно указать operationId.

```yaml
paths:
  /order/{id}/user:
    post:
      tags:
        - Order
      operationId: setOrderUser
      parameters:
        - name: id
          in: path
          schema:
            type: string
      requestBody:
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/User'
```

В этом случае все методы будут иметь название setOrderUser.
Соответственно, в контроллере тоже нужно описать метод с таким же именем.

```php
public function setOrderUser(string $id, User $bodyData)
{
    // code
}
```

Замечание по поводу длины путей. Желательно чтобы пути были не больше двух уровней. 
То есть допускаются пути /order/{id}/user, но не допускаются /order/{id}/user/roles и т.п.

