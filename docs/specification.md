# Визначення

## Клієнт і сервер

Поняття з клієнт-серверної архітектури. У загальному випадку це дві програми які можуть обмінюватись інформацією 
(зазвичай через інтернет мережу).

Сервер - відповідає за збереження інформації й надає клієнтам інтерфейс для її отримання, модифікації чи збереження.
Також інтерфейс може надавати клієнту функції для виконання функцій (на виконання обчислень, чи бізнес дій).

## Ресурс

Головною абстракцією в REST є ресурс. *Ресурс - будь-яка інформація, якій можна дати ім'я*. Наприклад документ (зображення),
сутність(замовлення, стаття, людина), колекція інших ресурсів, динамічне значення (погода у Львові) і т.п.

*Ресурс має стан*, що зберігається сервером. Клієнт може отримувати, або змінювати стан ресурсу за допомогою представлень.

*Представлення - це дані ресурсу в певному форматі*: JSON/XML/HTML/текст. Один ресурс може мати багато представлень.
Наприклад зображення - це ресурс, а JPEG, WEBM та інші формати - його представлення.

## URI

Для того щоб модифікувати ресурс нам потрібно якимось чином вказати який саме ресурс ми хочемо модифікувати, тобто
ідентифікувати його. Для цього використовується URI - Uniform Resource Identifier. Про URI можна думати як про рядок з
іменем, або псевдонімом ресурсу.

URL - найбільш відомий стандарт URI для ідентифікації ресурсів в інтернеті. URL - окремий випадок URI, конкретна його
реалізація.

# Структура представлення ресурсу

## Представлення ресурсу у відповіді на запит

Кожне представлення ресурсу у відповіді повинен наслідувати поля від openapi компонента ErrorResponse - якщо виникла 
помилка, SuccessResponse - для успішної відповіді.

```yaml
ErrorResponse:
    type: object
    properties:
        messages:
            type: array
            items:
                $ref: "#/components/schemas/Message"
            description: 
    description: >
        Список повідомлень про виконання запиту: помилки, попередження, або інша корисна для клієнта інформація.
Message:
    type: object
    properties:
        level:
            type: string
            enum: [`emergency`, `alert`, `critical`, `error`, `warning`, `notice`, `info`]
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
SuccessResponse:
    allOf:
        - $ref: '#/components/schemas/ErrorResponse'
    type: object
    properties:
        data:
            description: >
                Корисна інформація про стан ресурсу, або колекції ресурсів. Стан ресурсу описується набором полей 
                об'єкту, або примитивом (рядок, число і т.п.).
```

У відповіді **ПОВИННО** бути присутнім одне з полів: 'data', або 'messages'.

> Компоненти вище описані для openapi специфікації версії '3.0.0'. Згідно якої, якщо не вказано, що поле обов'язкове
> (його немає у масиві required), то цей ключ може бути відсутній у компоненті. Тобто у специфікації вище фактично
> дозволяється повернути пусту відповідь ('{}'). Тому ми накладаємо додаткові обмеження.

Якщо у відповіді відсутній ключ `data` (або його значення null), то у messages **ПОВИННО** бути, як мінімум, одне
повідомлення з `level` рівним `error` або вище.

> Рівні сортируются у порядку спадання наступним чином: `emergency`, `alert`, `critical`, `error`, `warning`, `notice`, 
> `info`

> Фактично за схемою поле 'data' не може мати значення null, але через помилку у старих версіях php генератора немає 
> можливості зовсім не повернути поле 'data' і воно повертається зі значенням 'null'. Після того як усі програми 
> перейдуть на нову версію генератора і виправлять помилку, то умову про 'null' можна буде прибрати зі специфікації.

```json5
{
  "messages": [
    // оскільки `data` відсутня, то хоча б одна помилка (повідомлення з рівнем 'error' або вище) повинна бути присутня
    {
      "type": "VALIDATION_ERROR",
      "level": "error",
      "text": "Track number has wrong format."
    }
  ]
}
```

Якщо `data` і `messages` одночасно присутні у відповіді, то `messages` **НЕ ПОВИНЕН** містити повідомлень з рівнем вище
ніж warning.

```json5
{
  "data": {
    "trackNumber": "...",
    "shippingMethod": "FedEx 2Day",
    "estimateDeliveryDate": null
  },
  "messages": [
    {
      "type": "NO_SHIPPING_ESTIMATES",
      "level": "warning",
      // не може бути error тому що `data` не пуста 
      "text": "No rules found to calculate estimates for 'FedEx 2Day'."
    }
  ]
}
```

## Представлення ресурсу запиту для POST, PUT, PATCH методів

Тіло запиту повинно наслідуватись від openapi компонента RequestBody.

```yaml
RequestBody:
  type: object
  required:
    - data
  properties:
    data:
      type: object
  description: Корисна інформація про ресурс. Тобто усі поля які описують стан ресурсу.

  ```http request
```

Приклад запиту на створення статті. 

```http request
POST /articles
Content-Type: application/json
Accept: application/json

{
  "data": {
    "title": "New article",
    "imgUrl": "http://example.com/images/new_article.png"
  }
}
```

# Long running tasks

Якщо запит виконується тривалий проміжок часу (хвилину і більше), то добре рішення оформити його як long running task.

## Структура

Long running task це [ресурс](#ресурс), який **ПОВИНЕН** містити наступні ключі:

- `id` - ідентифікатор, який генерується на стороні серверу.
- `status` - enum: [`pending`, `fullfilled`, `rejected`]
- `data` - данні необхідні для створення [ресурсу](#ресурс)
- `result` - об'єкт, що наслідується від ErrorResponse або SuccessResponse

Якщо задача зі статусом `pending`, то `result` **ПОВИНЕН** бути null

```json5
{
  "data": {
    "id": "12345",
    "status": "pending",
    "data": {
      "title": "...",
      "content": "..."
    },
    "result": null
  }
}
```

Якщо задача зі статусом `fulfilled`, то `result` **ПОВИНЕН** містити `data`.

```json5
{
  "data": {
    "id": "12345",
    "status": "fulfilled",
    "data": {
      "title": "...",
      "content": "..."
    },
    "result": {
      "data": {
        "article_id": '4352'
      }
    }
  }
}
```

Якщо задача зі статусом `rejected`, то `result` **ПОВИНЕН** містити `messages` з хоча б одною помилкою.

```json5
{
  "data": {
    "id": "12345",
    "status": "rejected",
    "data": {
      "title": "...",
      "content": "..."
    },
    "result": {
      "data": null,
      "messages": [
        {
          "type": "VALIDATION_ERROR",
          "level": "error",
          "text": "Title contains invalid characters."
        }
      ]
    }
  }
}
```

Long running task **МОЖЕ** містити ключі:

- `idempotencyKey` - ключ ідемпотентності.
- `stage` - рядок з більш детальним (ніж в status) станом задачі.
- `startTime` - date-time: дата початку виконання задачі в UTC.
- `endTime` - date-time: дата закінчення виконання задачі в UTC.
- `timeout` - int: максимальний час виконання задачі в секундах, після якого її потрібно скасувати. Скасована задача 
потрапляє у статус rejected.

## Створення long running task

Якщо передано ключ ідемпотентності, то, з таким ключем, допускається наявність тільки однієї задачі зі статусом
fulfilled або pending. Зі статусом rejected може бути будь-яка кількість задач з однаковим ключем ідемпотентності.

Якщо задача з переданим в запиті ключем ідемпотентності вже існує, то потрібно повернути цю задачу у відповіді.

Наприклад поставимо задачу на створення статті:

```http request
POST /articles/task
Content-Type: application/json
Accept: application/json

{
  "data": {
    "idempotency_key": "124",
    "data": {
      "title": "New article",
      "content": "A"
    }
  }
}
```

У відповідь отримуємо pending задачу з ідентифікатором 12345:

```json5
{
  "data": {
    "id": "12345",
    "status": "pending",
    "data": {
      "title": "New article",
      "content": "А"
    },
    "result": null
  }
}
```

У цей момент відправимо ще один ідентичний минулому запит

```http request
POST /articles/task
Content-Type: application/json
Accept: application/json

{
  "data": {
    "idempotency_key": "124",
    "data": {
      "title": "New article",
      "content": "A"
    }
  }
}
```

Оскільки задача у статусі pending, то у відповідь отримуємо ту саму задачу з ідентифікатором 12345, а нової не
створиться:

```json5
{
  "data": {
    "id": "12345",
    "status": "pending",
    "data": {
      "title": "New article",
      "content": "А"
    },
    "result": null
  }
}
```

Через якийсь час перевіримо статус задачі

```http request
GET /articles/task/12345
```

Отримаємо rejected задачу, оскільки поле 'content' не пройшло валідацію.

```json5
{
  "data": {
    "id": "12345",
    "status": "rejected",
    "data": {
      "title": "New article",
      "content": "А"
    },
    "result": {
      "data": null,
      "messages": [
        {
          "type": "VALIDATION_ERROR",
          "level": "error",
          "text": "Content should contains at least 5 characters."
        }
      ]
    }
  }
}
```

Виправляємо content і повторюємо створення з тим самим ключем ідемпотентності

```http request
POST /articles/task
Content-Type: application/json
Accept: application/json

{
  "data": {
    "idempotency_key": "124",
    "data": {
      "title": "New article",
      "content": "My first article!"
    }
  }
}
```

На сервері вже зберігається одна задача з ідентифікатором 12345 і ключем ідемпотентності 124. Але оскільки вона має
статус rejected, то сервер створює ще одну задачу з цим самим ключем ідемпотентності 124, але іншим ідентифікатором.

```json5
{
  "data": {
    "id": "54321",
    "status": "pending",
    "data": {
      "title": "New article",
      "content": "My first article!"
    },
    "result": null
  }
}
```

Через деякий час перевіряємо її статус

```http request
GET /articles/task/54321
```

І отримуємо задачу зі статусом fulfilled у якої в результаті є ідентифікатор створеної статті.

```json5
{
  "data": {
    "id": "54321",
    "status": "fulfilled",
    "data": {
      "title": "New article",
      "content": "My first article!"
    },
    "result": {
      "data": {
        "article_id": '1'
      }
    }
  }
}
```

За допомогою ідентифікатора можем отримати повну інформацію про статтю

```http request
GET /articles/1
```

```json
{
  "data": {
    "id": 1,
    "title": "New article",
    "content": "My first article!",
    "created_at": "..."
  }
}
```

## Скасування задачі

Задача **МОЖЕ** мати можливість бути скасованою декількома способами. 

1. Після закінчення таймауту (якщо такий був переданний). 
2. Посилання http - запиту. Поки без деталей як саме, але при необхідності потрібно їх уточнити і додати в специфікацію.

Після скасування задача потрапляє у rejected статус.
