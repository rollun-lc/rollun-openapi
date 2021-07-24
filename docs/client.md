# Использование сгенерированых клиентов

Информация для тех кто будет использует сгенерированные клиенты.

## Массивы в query при style = form, explode = true

Рассмотрим пример с параметром filters в query, который массив строк. При этом параметры
сериализации: style = form, explode = true

```yaml
- in: query
  name: "filters"
  required: false
  explode: true
  style: form
  explpde: true
  schema:
    type: array
    items:
      type: string
```

Для того чтобы передать массив из двух строк ['string1', 'string2'] под этим ключем

```php
$client->get(['filters' => ['filters' => 'string1', 'filters' => 'string2']]);
```

Сделано это для поддержки динамических ключей: https://github.com/OpenAPITools/openapi-generator/pull/3984/files#r351831130


##Конфигурация клиентов
С версии 9.* библиотека поддерживает конфигурирование обьектов Rest\*, Api\*, Configuration и Http клиента. Добавлены соответсвующие фабрики для каждого типа обьектов (кроме Http клиента).

Пример конфигурации api обьекта (например, если нужны разные обьекты с разными хостами):

```php
use OpenAPI\Client\Factory\RestAbstractFactory;
use OpenAPI\Client\Factory\ApiAbstractFactory;
use Test\OpenAPI\V1_0_1\Client\Rest\Test;
use Test\OpenAPI\V1_0_1\Client\Api\TestApi;

return [
    RestAbstractFactory::KEY => [
        'TestClient1' => [
            RestAbstractFactory::KEY_CLASS => Test::class,
            RestAbstractFactory::KEY_API_NAME => 'TestClientApi1'
        ],
        'TestClient2' => [
            RestAbstractFactory::KEY_CLASS => Test::class,
            RestAbstractFactory::KEY_API_NAME => 'TestClientApi2'
        ],
    ],
    ApiAbstractFactory::KEY => [
        'TestClientApi1' => [
            ApiAbstractFactory::KEY_CLASS => TestApi::class,
            ApiAbstractFactory::KEY_HOST_INDEX => 1,
        ],
        'TestClientApi2' => [
            ApiAbstractFactory::KEY_CLASS => TestApi::class,
            ApiAbstractFactory::KEY_HOST_INDEX => 2,
        ],
    ],
];
```

Пример конфигурации Http клиента (например, если нужно увеличить timeout):

```php
use OpenAPI\Client\Factory\ApiAbstractFactory;
use Test\OpenAPI\V1_0_1\Client\Api\TestApi;
use GuzzleHttp\Client;

return [
    ApiAbstractFactory::KEY => [
        'TestClientApi' => [
            ApiAbstractFactory::KEY_CLASS => TestApi::class,
            ApiAbstractFactory::KEY_HOST_INDEX => 1,
            ApiAbstractFactory::KEY_CLIENT => 'TestHttpClient'
        ],
    ],
    // Некая конфигурация для Http клиента
    'dependencies' => [
        'factories' => [
            'TestHttpClient' => function () {
                return new Client(['timeout' => 300]);
            }
        ]
    ]
];
```