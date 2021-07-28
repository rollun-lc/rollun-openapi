# Использование сгенерированых клиентов

Информация для тех кто будет использует сгенерированные клиенты.

## Массивы в query при style = form, explode = true

**!!! На данный момент этот вид сериализации не работает, проблема описана в [PR #26](https://github.com/rollun-com/rollun-openapi/issues/26)**

Сериализация следующего вида:

| URI template | Primitive value id = 5 | Array id = [3, 4, 5] | Object id = {"role": "admin", "firstName": "Alex"} |
|--------------|------------------------|----------------------|----------------------------------------------------|
| /users{?id}  | /users?id=5            | /users?id=3,4,5      | /users?id=role,admin,firstName,Alex                |

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

Для того чтобы передать массив из двух строк ['string1', 'string2'] под этим ключем нужно сделать вложенный массив, 
следующего вида:

```php
/** @var \OpenAPI\Server\Rest\RestInterface $client */
$client->get(['filters' => ['filters' => ['string1', 'string2']]]);
```

Сделано это для поддержки динамических ключей: https://github.com/OpenAPITools/openapi-generator/pull/3984/files#r351831130