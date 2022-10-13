# OpenAPI generator

Ця бібліотека містить php скрипт, що генерує код клієнтської або серверної сторони з openapi маніфесту. Скрипт працює 
за допомогою утиліти [openapi-generator](https://openapi-generator.tech/).

> Openapi маніфест - це документ, з певною структурою, що описує HTTP API: url шляхи, формати даних запиту/відповіді, 
> авторизацію і т.п. Детальніше можна почитати [тут](https://swagger.io/docs/specification/about/).
> 
> На основі цього маніфесту можна згенерувати код для клієнтської або серверної частини. 
> 
> Для клієнтської частини генерується API клієнт, за допомогою якого можна відправляти запити до данного Api.
> 
> Згенерованний код для серверної частини буде містити шаблон контролера, який потрібно імплементувати.
>
> І клієнт і сервер будуть містити валідацію та серіалізацію/десериалізацію даних з/в об'єкти для http запиту та 
> відповіді.

Також цю бібліотеку потрібно підключати в require секцію composer.json свого проекту, оскільки вона містить класи, що
потрібні для роботи згенерованого коду.

## Quick start

### Що таке openapi

Openapi - це формат файлу, який описує http API: формати запиту/відповіді та кінцеві точки.

Простий варіант openapi файлу, що описує api с однією кінцевою точкою `/users`, яка повертає массив імен користувачів,
виглядає наступним чином:

```yaml
openapi: 3.0.0
info:
  title: Sample API
  description: Optional multiline or single-line description in [CommonMark](http://commonmark.org/help/) or HTML.
  version: 0.1.9
servers:
  - url: http://api.example.com/v1
    description: Optional server description, e.g. Main (production) server
  - url: http://staging-api.example.com
    description: Optional server description, e.g. Internal staging server for testing
paths:
  /users:
    get:
      summary: Returns a list of users.
      description: Optional extended description in CommonMark or HTML.
      responses:
        '200':    # status code
          description: A JSON array of user names
          content:
            application/json:
              schema: 
                type: array
                items: 
                  type: string
```

Детальніше про формат openapi документу можна почитати в [swagger.io
документації](https://swagger.io/docs/specification/about/)

### Openapi generator

Цей файл може використовуватись різними інструментами, наприклад, swagger ui генерує інтерфейс с [інтерактивною
документацією](https://petstore.swagger.io/).

![Swagger ui](docs/img/swagger-ui.png)

Існує також інструмент [openapi-generator](https://openapi-generator.tech/), що дозволяє генерувати код на основі
openapi документу (маніфесту). Цей код може містити об'єкти запиту/відповіді, їх валідацію, дисеріалізацію та
серіалізацію з/в різні медіа типи, тести та т.п.

Генератори коди можна поділити на два типи: серверні та клієнтські. Які використовуються відповідно до того чи ваша
программа діє як клієнт чи сервер, чи обидва варіанти відразу (проксі сервер).

Серверні генератори генерують шаблони контроллерів, які программіст повинен імплементувати, щоб отримати валідний
сервер на який можна відправляти запити.

Клієнтські генератори генерують код, що дозволяє зручно відправляти запити на сервер та оброблювати відповідь.

Фактично наш генератор складається з двох генераторів: клієнтського і серверного, які запускаються командами
`generate:client` та `generate:server` відповідно.

### Написання маніфесту

Зазвичай маніфест пишеться або тим хто реалізовує api або тим кому потрібно api.

Для написання маніфестів ми використовуємо розгорнутий на наших серверах swagger-editor, доступний за посиланням:
[swagger-editor.rollun.net](https://swagger-editor.rollun.net). Цей редактор поєднанний з репозиторієм на гітхабі,
де зберігаються усі наші маніфести, та дозволяє відкривати або зберігати маніфести в цей репозиторій.

Для того, щоб спростити написання маніфестів у нас існує манфест шаблон, під назвою skeleton. Його можна знайти
натиснувши кнопку open manifests в swagger-editor:

![Swagger editor open manifests button](docs/img/swagger-editor-open-manifests.png)

Після чого відкриється вікно вибору маніфесту, в якому можна знайти skeleton маніфест.

![Swagger editor choosing skeleton manifests](docs/img/swagger-editor-choosing-skeleton.png)

Детальніше про правила створення маніфеста можна почитати в [manifests.md](docs/manifest.md)

### Запуск генератора

Встановіть бібліотеку у свій проект(мікросервіс):

   ```composer require rollun-com/rollun-openapi```

**Важливо** Після того, як композер відпрацює, перевірьте що у файлі `/config/config.php` 
присутній конфіг провайдер `\OpenAPI\ConfigProvider::class`, а також він завантажується після
`\Zend\Expressive\Router\FastRouteRouter\ConfigProvider::class` інакше не буде працювати.

Після цього вам через php потрібно запустити скрипт [./bin/openapi-generator](bin/openapi-generator) даної бібліотеки
з командою `generate:server`, якщо ви хочете згенерувати код для серверної частини, і, відповідно `generate:client`
для клієнта. Шлях до маніфесту скрипт запитає сам, але також його можна відразу вказати через параметр `-m`. 

> Якщо ви встановили цю бібліотеку через composer у свій проект, то цей скрипт буде знаходитись за шляхом
> `./vendor/bin/openapi-generator`, а не `./bin/openapi-generator`

**Важливо,** щоб не отримати помилку, цей скрипт повинен запускатись в оточені, де встановлено утиліту 
[openapi-generator](https://openapi-generator.tech/). Це можна добитись двома шляхами:
1. Встановити [openapi-generator](https://openapi-generator.tech/) собі у систему локально, за інструкцією на їх сайті.
2. Використовувати docker, та запускати цей скрипт всередині докер контейнеру.

### Зуапуск генерації через докер

```bash
docker run --rm \
  -v $PWD:/var/www/app \
  maxrollundev/php-openapi-generator:8.0 \
  php vendor/bin/openapi-generator generate:server \
  -m openapi.yaml
```

Де:
- `-v $PWD:/var/www/app` - створює [волюм](https://docs.docker.com/storage/volumes/) з поточної директорії хост машини, до директорії 
/var/www/app контейнеру (цей шлях зручно використовувати, адже для цього контейнеру він є робочою директорію по 
замовчуванню)
- `maxrollundev/php-openapi-generator:8.0` - назва контейнеру (8.0 - версія php)
- `php vendor/bin/openapi-generator generate:server` - безпосередньо запуск скрипту генератора (для клієнта замінити 
`generate:server` а `generate:client`)
- `-m openapi.yaml` - шлях до маніфесту (може бути url)

Якщо ви використовуєте docker-compose в проекті, то в розділ services можна додати сервіс генератора

```yaml
services:
  # ...
  
  php-openapi-generator:
    image: maxrollundev/php-openapi-generator:8.0
    volumes:
      - ./:/var/www/app
```

та запускати генератор за допомогою 

```bash
docker-compose run --rm php-openapi-generator \
  php vendor/bin/openapi-generator generate:server \
  -m openapi.yaml
```

### Запуск генерації без докеру

1. Установите [openapi-generator](https://openapi-generator.tech/) ниже 5й (не включительно). Для проверки выполните команду:

   ```openapi-generator version```, в случае когда openapi-generator установлен вы увидите версию генератора.

   **ВЕРСИЯ ГЕНЕРАТОРА ДОЛЖНА БЫТЬ НИЖЕ ПЯТОЙ.** Связанно это с тем что в 5й версии [убрали](https://github.com/OpenAPITools/openapi-generator/pull/8145/commits) 
   генератор которым мы пользуемся, ему изменили имя и переделали для Laminas вместо Zend.

2. Для генерации кода выполните команду:

   ```php vendor/bin/openapi-generator generate:server```
   или
   ```php vendor/bin/openapi-generator generate:client```

   Команда поддерживает параметры. Передаются в виде --name=value.
   На данный момент реализовано указание манифеста (параметр manifest) в виде пути или урла.
   Например
   
   ```php vendor/bin/openapi-generator generate:client --manifest=openapy.yaml```
   
### Налаштування після генерації

Обязательно добавьте сгенерированные классы в аутолоадер композера.
```
"autoload": {
  "psr-4": {
    "SomeModule\\": "src/SomeModule/src/"
  }
},
```

Де, SomeModule - це title маніфесту

### Якщо виникли помилки

1. Проверьте что в контейнере есть `rollun\logger\LifeCycleToken`.

   Под этим именем в контейнере должна находиться строка с идентификатором текущего жизненного цикла приложения.

   Рекомендованный способ это установить библиотеку rollun-com/rollun-logger. В комплекте с которой идет LifeCycleToken.
   Почитать о том как установить его в контейнер можно в [документации](https://github.com/rollun-com/rollun-logger/blob/master/docs/index.md#lifecycletoken)
   библиотеки.

### Використання згенерованого сервера

Серверний генератор генерує шаблони контролерів, які потрібно реалізувати програмістові. Шаблони контролера знаходиться
за шляхом `src/{ManifestTitle}/src/OpenaAPI/{ManifestVersion}/Server/Rest`. Наприклад 
[User.php](src/HelloUser/src/OpenAPI/V1/Server/Rest/User.php) маніфесту [openapi.yaml](openapi.yaml)

```php
<?php

namespace HelloUser\OpenAPI\V1\Server\Rest;

use Articus\DataTransfer\Service as DataTransferService;
use OpenAPI\Server\Rest\Base7Abstract;
use Psr\Log\LoggerInterface;
use rollun\dic\InsideConstruct;

/**
 * Class User
 */
class User extends Base7Abstract
{
	public const CONTROLLER_OBJECT = 'User1Controller';

	/** @var object */
	protected $controllerObject;

	/** @var LoggerInterface */
	protected $logger;

	/** @var DataTransferService */
	protected $dataTransfer;


	/**
	 * User constructor.
	 *
	 * @param mixed $controllerObject
	 * @param LoggerInterface|null logger
	 * @param DataTransferService|null dataTransfer
	 *
	 * @throws \ReflectionException
	 */
	public function __construct($controllerObject = null, $logger = null, $dataTransfer = null)
	{
		InsideConstruct::init([
		    'controllerObject' => static::CONTROLLER_OBJECT,
		    'logger' => LoggerInterface::class,
		    'dataTransfer' => DataTransferService::class
		]);
	}


	/**
	 * @inheritDoc
	 *
	 * @param \HelloUser\OpenAPI\V1\DTO\User $bodyData
	 */
	public function post($bodyData = null)
	{
		if (method_exists($this->controllerObject, 'post')) {
		    $bodyDataArray =$this->dataTransfer->extractFromTypedData($bodyData);

		    return $this->controllerObject->post($bodyDataArray);
		}

		throw new \Exception('Not implemented method');
	}


	/**
	 * @inheritDoc
	 */
	public function getById($id)
	{
		if (method_exists($this->controllerObject, 'getById')) {
		    return $this->controllerObject->getById($id);
		}

		throw new \Exception('Not implemented method');
	}
}
```

Саме методи `post`, `getById` цього класу будуть викликатись при обробці запитів. Як видно цей клас делегує ці методи
деякому `controllerObject`. Цей `controllerObject` це клас який повинен створити програміст, в якому написати реалізацію
усіх потрібних методів (`post`, `getById` в даному випадку). Приклад 
[UserController](src/HelloUser/src/User/Controller/V1/UserController.php). Після чого розмістити цей клас в dependency 
injection контейнері під ім'ям з константи CONTROLLER_OBJECT, в данному випадку 'User1Controller'. Це
простіше всього зробити прописавши alias в конфігурації: 
[приклад](https://github.com/rollun-com/rollun-openapi/blob/ff35e8a6f6e9274fb03aba2173742a87750f5fa6/config/autoload/hello_user.global.php#L10)

### Використання згенерованого клієнта

З клієнтом все простіше, від програміста не потрібно ніяких додаткових дій після генерації. Аналогічно серверу в 
директорію `src/{ManifestTitle}/src/OpenaAPI/{ManifestVersion}/Client/Rest` генеруються класи Api клієнтів, що дозволяють
відправляти запити. 

Потрібний клас можна дістати із контейнера і він вже готовий до використання.

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
| 3 | get        | GET         | /order      | Получение коллекции           |
| 4 | delete     | DELETE      | /order      | Удаление коллекции(?)         |
| 5 | idGet      | GET         | /order/{id} | Получение сущности            |
| 6 | idPatch    | PATCH       | /order/{id} | Частичное обновление сущности |
| 7 | idPut      | PUT         | /order/{id} | Замена сущности               |
| 8 | idDelete   | DELETE      | /order/{id} | Удаление сущности             |


Сейчас можно генерировать любые другие PHP методы с любыми другими путями.
Например, нужно сгенерировать метод, который будет обрабатывать POST запрос по пути /order/{id}/user.
Для этого, в первую очередь, необходимо добавить нужный путь в манифест.
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

В этом случае будет сгенерирован метод OrderIdUserPost. То есть методы в классах (Handler, Rest, Api) будут генерироваться автоматически по следующей схеме - путь + http метод (все в camelCase).
В контроллере необходимо описать метод с таким же названием.
Этот метод будет принимать 2 параметра - $id и $bodyData, то есть в контроллер должен выглядеть примерно так:

```php
public function orderIdUserPost(string $id, User $bodyData)
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

# Запуск тестов

## С помощью docker

Нужно чтобы в системе были установлены:

- docker
- docker-compose
- утилита make

Достаточно сначала запустить `make up` чтобы запустить приложение. После чего для выполнения тестов `make test`.
Чтобы остановить приложение запустите `make down`.

## Без docker

Тесты можно запустить через `composer test`. Внутри некоторых тестов поднимается встроеный php сервер и слушает порт 
8081, так что важно чтобы он был сводобен.
