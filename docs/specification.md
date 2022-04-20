# Структура відповіді

## Верхній рівень

Кожна відповідь на верхньому рівні **ПОВИННА** мати хоча б один з перерахованих нижче ключів:

- `data` - головні данні. Можуть бути [ресурсом](#ресурс), списком (масивом) [ресурcів](#ресурс), чи null
- `messages` - список [повідомлень](#повідомлення) про виконання запиту: помилки, попередження, або інша корисна для
  клієнта інформація.

Якщо у відповіді відсутній ключ `data` (або null), то у messages **ПОВИННО** бути, як мінімум, одне
повідомлення з `level` рівним `error`.

```json5
{
  "data": null,
  "messages": [
    {
      "type": "VALIDATION_ERROR",
      "level": "error",
      // оскільки `data` пуста, то хоча б одна помилка повинна бути присутня
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

## Ресурс

Ресурс **ПОВИНЕН** містити принаймні один ключ.

## Повідомлення

Повідомлення **ПОВИННО** містити **ТІЛЬКИ** наступні ключі:

- `type` - enum з типом помилки. UNDEFINED за замовчуванням.
- `level` - enum: [`emergency`, `alert`, `critical`, `error`, `warning`, `notice`, `info`]
- `text` - довільний текст з поясненням помилки для людини.

## Створення ресурсу

Для створення ресурсу використовується метод POST на url колекції ресурсу. Запит **ПОВИНЕН** містити ключ `data` з
об'єктом [ресурсу](#ресурс).

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
- `result` - об'єкт аналогічний [структурі відповіді](#структура-відповіді)

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
- `timeout` - int: максимальний час виконання задачі в секундах, після якого її потрібно зупинити.

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
