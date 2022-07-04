# 1. Api специфікація

## Яка різниця між PUT і PATCH запитом?

Різниця між запитами PUT і PATCH відображається в тому, як сервер обробляє об’єкт, щоб змінити ресурс, 
ідентифікований за допомогою Request-URI. PUT запит містить об’єкт, що вважається модифікованою версією ресурсу,
який зберігається на сервері, і клієнт просить замінити збережену версію. В PATCH вкладений об’єкт 
містить набір інструкцій, що описують, як ресурс, який зараз знаходиться на вихідному сервері, повинен бути змінений 
для створення нової версії. Метод PATCH МОЖЕ мати побічні ефекти на інші ресурси; тобто нові ресурси можуть бути 
створені або змінені існуючі за допомогою застосування виправлення.

PATCH МОЖЕ створити новий ресурс залежно від типу patch документу.

PATCH описує набір змін в «patch документі», що визначається за допомогою media-type. Хоча явно ніде не написано, але
в загальному випадку application/json - не являється patch документом. Адже сервер ніяк не зможе зрозуміти яким чином
йому змінювати ресурс.

Patch документ зазвичай може приймати одну з двох форм:

- **Містити чіткий опис змін.** Приклад [json-patch](https://datatracker.ietf.org/doc/html/rfc6902/)

The original document
```json
{
  "title": "hello, world!",
  "author": "John",
  "tags": ["example"],
  "content": "My article."
}
```

The patch
```json
[
  { "op": "replace", "path": "/title", "value": "First article!" },
  { "op": "add", "path": "/tags", "value": ["sample"] },
  { "op": "remove", "path": "/author" }
]
```

The result

```json
{
  "title": "First article!",
  "tags": ["example", "sample"],
  "content": "My article."
}
```

- **Містити модифіковану версію ресурсу і дозволити серверу самому визначити набір змін.** Приклад [json/merge-patch](https://datatracker.ietf.org/doc/html/rfc7396)

The original document
```json
{
  "baz": "qux",
  "foo": "bar",
  "name": "fred"
}
```

The patch
```json5
{
  "title": "First article!", // { "op": "replace", "path": "/title", "value": "First article!" }
  "hello": ["world"], // { "op": "replace", "path": "/hello", "value": ["world"] }
  "foo": null // { "op": "remove", "path": "/foo" }
}
```

The result

```json
{
  "baz": "boo",
  "hello": ["world"],
  "name": "fred"
}
```

Мінус json-merge-patch в тому що він не може описати нормально описати деякі операції: наприклад додавання елементу 
в массив.


Мені здається ми можемо зробити свій rollun-json-patch з меншим набіром операції ніж в json-patch. Або підтримувати
обидва варіанти. 

TODO:
1. Прийняти рішення який механізм ми будемо використовувати
2. Додати рішення в специфікацію

## Як виконувати версіонування API?

Версіонування api залишаємо в url, це дозволить простіше кешувати ресурси (адже uri однозначно співвідноситься з даними
які повертаються), а також дозволить вказувати версію при відправці get запиту через браузер.

В маніфесті будем застосовувати семантичне версіонування. Це дозволить, при мінорних змінах, серверам розуміти що в них 
імплементується не остання версія і її потрібно обновити.

```yaml
openapi: "3.0.0"
info:
  title: petShop
  description: API exposing my pet shop’s functionality
  version: "2.1.2"
servers:
  - url: https://example.org/petShop/v2
```

Можливий підхід як в [gcloud](https://www.belgif.be/specification/rest/api-guide/#api-versioning), коли в url міститься
тільки мажорна версія, тобто сервер завжди підтримує одну мінорну і патч версію. Це дозволить залишити простоту в 
реалізації серверу. І можливо ніяк не буде впливати на клієнта, якому важлива тільки мажорна версія. Це стане 
зрозумілішим коли розпишемо співвідношення між змінами в API та версією.

TODO:
1. Написати які зміни Api приводять до зміни якої версії: мажорної, мінорної чи патч

## Як передавати lifecycle_token?

Мені подобається підхід який в нас реалізований вже: коли застосунок очікує, що lifecycle_token буде в контейнері,
і не важливо яким чином він туди потрапив:
- Згенерувався в middleware оскільки в запиті до сервера його не було
- Middleware отримав його з хедеру 

І в усіх випадках не важливо чи це був запит до openapi, datastore чи callback.

При цьому не бачу потреби передавати його в dto, оскільки якщо якомусь классу буде потрібен цей lifecycle_token 
він може прийняти його в конструкторі (як це робить логер).

TODO:
1. Написати в специфікації про те як через http передавати lifecycle_token, та зобов'язати його передавати, якщо
запит був ініційований мікросервісом.

## Як повертати історію виконання лонг-таску?

Історія може повертатись окремим http запитом.

## Як працюють рейт ліміти?

Можна повертати інформацію про ліміти в хедерах, як це робить [github](https://docs.github.com/en/rest/overview/resources-in-the-rest-api#rate-limit-http-headers)

```bash
$ curl -I https://api.github.com/users/octocat
> HTTP/2 200
> Date: Mon, 01 Jul 2013 17:27:06 GMT
> x-ratelimit-limit: 60
> x-ratelimit-remaining: 56
> x-ratelimit-reset: 1372700873
```

| Header Name           | 	Description                                                |
|-----------------------|-------------------------------------------------------------|
| x-ratelimit-limit     | 	Дозволена кількість запитів в годину                       |
| x-ratelimit-remaining | Кількість запитів що залишилась в поточному лімітному вікні |
| x-ratelimit-reset     | Час через який лімітне вікно обновиться в UNIX              |

> Для рейт ліміт хедерів також є [драфт специфікації](https://tools.ietf.org/id/draft-polli-ratelimit-headers-00.html#:~:text=The%20RateLimit%2DLimit%20response%20header,it%20MAY%20not%20be%20served.&text=The%20expiring%2Dlimit%20value%20MUST,closer%20to%20reach%20its%20limit.), 
> в цілому там тіж самі три хедера, але кожен запит ще має request-quota - те за скільки запитів буде вважать цей запит.
> Наприклад один складний запит можна вважати за два запити чи більше. 

Також github мають окремий ендпоінт, що повертає ліміти по кожному ресурсу:

``` http request
GET /rate_limit
```

```json
{
  "resources": {
    "core": {
      "limit": 5000,
      "remaining": 4999,
      "reset": 1372700873,
      "used": 1
    },
    "search": {
      "limit": 30,
      "remaining": 18,
      "reset": 1372697452,
      "used": 12
    },
    "graphql": {
      "limit": 5000,
      "remaining": 4993,
      "reset": 1372700389,
      "used": 7
    },
    "integration_manifest": {
      "limit": 5000,
      "remaining": 4999,
      "reset": 1551806725,
      "used": 1
    },
    "code_scanning_upload": {
      "limit": 500,
      "remaining": 499,
      "reset": 1551806725,
      "used": 1
    }
  },
  "rate": {
    "limit": 5000,
    "remaining": 4999,
    "reset": 1372700873,
    "used": 1
  }
}
```

Можливо є сенс замість одного ендпоінта робити окремий для кожного ресурсу: /orders/rate-limits, /customers/rate-limits.
Мені здається так краще, тому що буде повертатись інформація тільки щодо одного ресурсу, що наразі цікавий клієнту.

Якщо використовувася [conditional request](https://docs.github.com/en/rest/overview/resources-in-the-rest-api#conditional-requests) 
і повернувся код 304 Not Modified, то цей запит не враховується в рейт лімітах.

Якщо ліміти закінчились повертається 429 код відповіді.

```bash
$ curl -I https://api.github.com/users/octocat
> HTTP/2 429
> Date: Mon, 01 Jul 2013 17:27:06 GMT
> x-ratelimit-limit: 60
> x-ratelimit-remaining: 0
> x-ratelimit-reset: 1372700873
```

TODO:
1. Дослідити різні алгоритми реалізації rate лімітів.
2. Пошукати бібліотеки які реалізовують rate ліміти для php.
3. Написати вимоги до openapi генератора, що пов'язані з rate лімітами

## Чи потрібно використовувати теги в openapi маніфесті?

Я би писав наш генератор так, щоб використання тегів було не обов'язковим. Але при наявності їх можна 
використовувати, як це робить стандартний php генератор клієнта. Якщо не використовувати ніяких тегів він генерує 
один клас клієнта: DefaultApi у якому доступні усі методи. Якщо ж задати теги, то він генерує класи під кожен тег:
ArticleApi, OrdersApi і т.п. в яких доступні тільки ті методи, що визначені під цим тегом в маніфесті. 

Мені здається за відсутності тегів можна використовувати url щоб їх замінити, тобто щоб url '/articles' генерувався
так, ніби йому присвоєно тег Articles. Але при наявності тега не враховувати url і використовувати його. 

Одна операція може мати декілька тегів, при цьому можна генерувати декілька класів по назвам цих тегів в кожному з 
якого буде ця операція. 

Можливо вибір чи використовувати теги чи ні зручно зробити у вигляді опції в команднії строці. На випадок якщо теги
названі не зручними іменами, а ми на це не можемо впливати (сторонні маніфести).

TODO:
1. Прийняти рішення чи будемо ми використовувати теги для генерації (при їх наявності) чи ні.
2. Написати вимоги до openapi генератора, що пов'язані з тегами 

## Як реалізувати часткове отримання ресурсу?

Для цього можна використовувати select в rql.

```http request
GET /articles/1
```

```http
HTTP/1.1 200 OK

{
  "data" : {
    "id": 1,
    "title": "My article",
    "content": "..."
  }
}
```

```http request
GET /articles/1?select=id
```

```http
HTTP/1.1 200 OK

{
  "data" : {
    "id": 1
  }
}
```

Головна проблема це як описати цю операцію в маніфесті. Якщо в нас наприклад по замовчуванню title завжди повинен 
повертатись.

Нам або потрібно робити усі поля optional, або описувати окремий ендпоінт для select запиту.

Можливе рішення 1:

- В required описувати тільки ті поля, що повернуться в будь якому разі. 
- В select використовувати лише опциональні поля, а required будуть повертатись в будь-якому випадку.
- Якщо клієнт використовує поле, що не вказане в required, то він обов'язково повинен вказати його в select. Код можна
генерувати так, щоб він перевіряв, що від сервера повернулись усі поля, що вказані в select.

## Як оброблювати помилку виконання лонг таску?

Створення задачі

```http request
POST /articles
Accept: application/vnd.rollun+json, application/vnd.rollun-error+json, application/vnd.rollun-long-task+json
Content-Type: application/vnd.rollun+json

{
  "data": {
    "title": "My article!"
  }
}
```

```http
HTTP/1.1 202 Accepted
Content-type: application/vnd.rollun-long-task+json
Retry-After: 30

{
  "data": {
    "id": "123",
    "stage": "creating"
  }
}
```

Отримання задачі

```http request
GET /articles/actions/post/123
Accept: application/vnd.rollun+json, application/vnd.rollun-error+json, application/vnd.rollun-long-task+json
```

```http
HTTP/1.1 202 Accepted
Content-type: application/vnd.rollun-long-task+json
Retry-After: 10

{
  "data": {
    "id": "123",
    "stage": "creating"
  }
}
```

Помилка

```http request
GET /articles/actions/post/123
Accept: application/vnd.rollun+json, application/vnd.rollun-error+json, application/vnd.rollun-long-task+json
```

```http
HTTP/1.1 200 OK
Content-type: application/vnd.rollun-error+json

{
  "messages": [
    {
      "level": "error",
      "type": "UNDEFINED_ERROR",
      "text": "Something went wrong"
    }
  ]
}
```

Успішно виконана задача

```http request
GET /articles/actions/post/123
Accept: application/vnd.rollun+json, application/vnd.rollun-error+json, application/vnd.rollun-long-task+json
```

```http
HTTP/1.1 200 OK
Content-type: application/vnd.rollun+json

{
  "data": {
    "id": 1,
    "title": "My article!"
  }
}
```

## Опис медіа типів

### Схема опису

- **Parent:** батьківський медіа тип. Описуваний медіа тип містить усі властивості батківського типу, 
якщо явно не написано інше.
- **Status codes**: список статус кодів для яких допускається використання описуваного медіа типу

### application/vnd.rollun+json

- **Parent:** application/json
- **Status codes**: 2xx

Медіа тип призначений для успішних відповідей, а також для POST і PUT запитів. 

Завжди json об'єкт.

Об'єкт **ПОВИНЕН** містити поле `data` з основною інформацією про стан ресурсу. Може бути null для опису пустого 
ресурсу, наприклад для опису успішного виконання задачі, що не має результату. Або при відсутності вхідних даних для
виконання запиту.

Об'єкт **МОЖЕ** містити поле `messages`. Рівень повідомлення **ПОВИНЕН** бути один з: `info`, `notice`, `warning`.

Openapi schema:

```yaml
SuccessResponse:
    type: object
    required:
        - data
    properties:
        data:
            nullable: true
            description: >
                Корисна інформація про стан ресурсу, або колекції ресурсів. Стан ресурсу описується набором полей 
                об'єкту, або примитивом (рядок, число і т.п.).
        messages:
            type: array
            items:
                $ref: "#/components/schemas/Message"

Message:
    type: object
    required:
        - level
        - type
        - text
    properties:
        level:
            type: string
            enum: [ `emergency`, `alert`, `critical`, `error`, `warning`, `notice`, `info` ]
        type:
            type: string
            enum:
                - UNDEFINED
            description: >
                Тип повідомлення для зручного розрізняння помилки клієнтською програмою.
                Назви типів повинні бути у верхньому регістрі, а слова розділені нижнім 
                підкреслюванням '_' (e.g. VALIDATION_ERROR).
                UNDEFINED - тип за замовчуванням
        text:
            type: string
            description: довільний текст з поясненням для людини
```

### application/vnd.rollun-error+json

- **Parent:** application/json
- **Status codes**: 2xx, 4xx, 5xx

Медіа тип призначений для опису помилок при створенні чи отриманні ресурсу і **НЕ ПОВИНЕН** використовуватись у запитах.

Завжди json об'єкт.

Об'єкт **НЕ ПОВИНЕН** містити поле `data`.

Об'єкт **ПОВИНЕН** містити поле `messages`. Рівень повідомлення **ПОВИНЕН** бути один з: `emergency`, `alert`, 
`critical`, `error`, `warning`, `notice`, `info`. Список `messages` **ПОВИНЕН** містити хоча б одне повідомлення з 
рівнем `error` або вище, що буде описувати причину помилки.

> Рівні сортируются у порядку спадання наступним чином: emergency, alert, critical, error, warning, notice, info

Openapi schema:

```yaml
SuccessResponse:
    type: object
    properties:
        messages:
            type: array
            items:
                $ref: "#/components/schemas/Message"
            minItems: 1
            description: At leas one item with level error or higher

Message:
    type: object
    required:
        - level
        - type
        - text
    properties:
        level:
            type: string
            enum: [ `emergency`, `alert`, `critical`, `error`, `warning`, `notice`, `info` ]
        type:
            type: string
            enum:
                - UNDEFINED
            description: >
                Тип повідомлення для зручного розрізняння помилки клієнтською програмою.
                Назви типів повинні бути у верхньому регістрі, а слова розділені нижнім 
                підкреслюванням '_' (e.g. VALIDATION_ERROR).
                UNDEFINED - тип за замовчуванням
        text:
            type: string
            description: довільний текст з поясненням для людини
```

## application/vnd.rollun-long-task-pending+json
- **Parent:** application/vnd.rollun+json
- **Status codes**: 200, 202

Призначений для опису асинхронної операції (задачі). Тип **НЕ ПОВИНЕН** використовуватись для опису операції яка вже 
виконалась. Для опису результату операції рекомендується використовувати типи `application/vnd.rollun+json` та 
`application/vnd.rollun-error+json` в залежності від того чи виконання завершилось успішно, чи з помилкою.

В `data` **ПОВИНЕН** міститись об'єкт `long-task`.

Об'єкт `long-task` **ПОВИНЕН** містити поля:
- `id` : string - ідентифікатор задачі.

> Нам не потрібне поле status, тому що цей тип використовується лише при pending статусу операції. 
> Для більш детального опису стану виконання можна використовувати поле `stage`.

Об'єкт `long-task` **МОЖЕ** містити поля:
- `idempotency-key` - ключ ідемпотентності
- `stage` : string - етап виконання задачі, може бути enum
- `percentComplete`: int[0-100] - стан виконання задачі у відсотках
- `createdAt`: date-time - час створення задачі
- `startedAt`: date-time - час початку виконання задачі

При використанні цього типу **РЕКОМЕНДУЄТЬСЯ** повертати хедер `Retry-After`, що буде описувати естімейт, коли
задача завершиться.

## Що повинні повертати запити, якщо лонг-таск видалено?

404 Not Found- якщо ідентифікатор задачі звільнено і в подальшому під цим ідентифікатором може з'явитись нова задача.
Навіть якщо ми використовуємо uuid, адже все одно ми ніде не зберігаємо інформацію що ідентифікатор зарезервовано.

410 Gone - Якщо інформацію про задачу видалено, але ми десь запам'ятали, що цей ідентифікатор колись був зайнятий.

## Яка інформація повинна передаватись в messages?

Типи повідомлень які можуть потрапити в messages:

- **Опис помилки при виконанні запиту**

```http request
GET /orders
```

```http
HTTP/1.1 500 Internal Server Error

{
  "messages": [
    {
      "level": "error",
      "type": "DATABASE_PROBLEM",
      "message": "Database not available. Try again later."
    }
  ]
}
```

- **Метаінформація про ресурс**

Наприклад штат у якому найбільше замовлень, чи попередження про несвіжість даних: в данном контексті саме несвіжість
усієї колекції, а не даних в якомусь конкретному замовлені. Тобто в колекції може не бути якогось елементу, але
усі інші елементи колекції містять актуальну інформацію.

```http request
GET /orders
```

```http
HTTP/1.1 200 OK

{
  "data": [
    //... 
  ], 
  "messages": [
    {
      "level": "info",
      "type": "MOST_POPULAR_STATE",
      "message": "Texas"
    },
    {
      "level": "warning",
      "type": "DATA_IS_NOT_FRESH",
      "message": "Last collection update was 2 days ago."
    }
  ]
}
```

- **Метаінформація про запит**

```http request
GET /orders
```

```http
HTTP/1.1 200 OK

{
  "data": [
    //... 
  ], 
  "messages": [
    {
      "level": "info",
      "type": "REQUEST_TIME",
      "message": "0.1 sec"
    }
  ]
}
```

- **Попередження та метаінформація пов'язані з конкретним елементом колекції**

Наприклад в деяких з повернених замовлень спосіб відправки не збігається з тим який ми запитували. Або якись
конкретний елемент колекції містить не актуальну інформацію, хоча сама колекція в актуальному стані.

```http request
GET /resources
```

```http
HTTP/1.1 200 OK

{
  "data": [
    //... 
  ], 
  "messages": [
    {
      "level": "info",
      "type": "SHIPPINH_METHOD_MISMATCH",
      "message": "Order AU001 has incorrect carrier. Requested 'USPS' Actual 'FedEx'."
    },
    {
      "level": "warning",
      "type": "ELEMENT_DATA_IS_NOT_FRESH",
      "message": "Order RM002 has las update 2 days ago."
    }
  ]
}
```

Ось такого роду помилки не зручно передавати в messages, адже доведеться якимось чином з'ясовувати, до якого саме
елементу колекції відноситься помилка. Куди зручніше записувати такі попередження як частину інформації про елемент.

```http 
HTTP/1.1 200 OK

{
  "data": [
    {
      "number": "AU001",
      // ... ,
      "messages": [
        {
          "level": "warning",
          "type": "SHIPPINH_METHOD_MISMATCH",
          "message": "Order AU001 has incorrect carrier. Requested 'USPS' Actual 'FedEx'."
        }
      ]
    },
    {
      "number": "RM002",
      // ... ,
      "messages": [
        {
          "level": "warning",
          "type": "DATA_IS_NOT_FRESH",
          "message": "Order RM002 has las update 2 days ago."
        }
      ]
    }
  ]
}
```

# 2. Openapi генератор

## Як повинен працювати вибір сервера?

Вимоги:

- При зміні порядку серверів в маніфесті в коді не повинен змінитись сервер на який відправляються запити
- Потрібна можливість вибирати сервер випадково (для балансування навантаження), або почергово (для надійності)

В коді можна ідентифікувати сервер за допомогою рядка. Порівняння може виконуватись за допомогою parse_url, окремо
для scheme, host і path. Клієнтський код, при виборі сервера, обов'язково повинен вказати host, але scheme і path для 
зручності можуть бути опціональними, якщо можливо однозначно визначити який хост мав на увазі клієнт.

```yaml
servers:
  - url: https://api.foo.com/v1
  - url: https://bar.com/v1
  - url: http://bar.com/v1
```

```php
$client->setHost('https://api.foo.com/v1'); // Ok
$client->setHost('https://api.foo.com'); // Ok
$client->setHost('api.foo.com/v1'); // Ok
$client->setHost('api.foo.com'); // Ok
$client->setHost('bar.com'); // Ok

$client->setHost('http://api.foo.com/v1'); // Error (scheme in manifest is https not http)
$client->setHost('api.foo.com/v1/users'); // Error (path in manifest is /v1 not /v1/users)
$client->setHost('bar.com/v1'); // Error (bar.com has 2 available scheme: http and https)
$client->setHost('foo.com'); // Error (host in manifest is api.foo.com not foo.com)
$client->setHost('/v1'); // Error (host required)
```

Для того щоб можна було зручно робити балансування навантаження, або почергову відправку запитів можна також зробити 
індексування за числом (індекс 0: https://api.foo.com/v1; індекс 1: https://bar.com/v1). Але це може привести до помилок. 
Наприклад, якщо серед серверів вказано тестовий сервер.

```yaml
servers:
  - url: https://api.example.com/v1
    description: Production server (uses live data)
  - url: https://sandbox-api.example.com:8443/v1
    description: Sandbox server (uses test data)
```

Тому краще передбачити можливість явно вказати в конфігурації взаємозамінні сервери і тільки їх використовувати 
для балансування навантаження, або повторів запитів.

```php
<?php

return [
    'manifest_config' => [
        'interchangeably_servers' => [
            westeurope.foo.com,
            southeastasia.foo.com
        ]
    ]
];
```

В цїй самій конфігурації потрібно дати можливість вказати, що замість одного сервера потрібно використовувати будь-який
з перерахованих в 'interchangeably_servers'. А також вказати стратегію за допомогою якої буде обиратись сервер.

```php
<?php

return [
    'manifest_config' => [
        'interchangeably_servers' => [
            // ...
        ],
        'server_choosing_strategy' => [
            'class' => SequentialServerSelectionStrategy::class,
            'options' => null
        ] 
    ]
];
```

Також було б добре передбачити можливість в конфігурації вказати сервер по замовчуванню, але залишити цю можливість
опціональною. Якщо не вказано сервер по замовчуванню, то використовувати перший по-порядку.

Варіанти того як краще організувати конфігурацію можна ще продумати, я лише приблизно вказав як це може виглядати.

## Як повинна працювати авторизація?

## Як реалізувати підтримку oneOf?

## План впровадження нових можливостей по версіям

### Версія 1

### Версія 2

### Версія 3

- Кешування інкапсульоване клієнтом