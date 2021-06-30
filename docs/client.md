# Использование сгенерированых клиентов

Информация для тех кто будет использует сгенерированные клиенты.

## Массивы в query при style = form, explode = true

Рассмотрим пример с параметром filters в query, который массив строк. При этом параметры
сериализации: style = form, explode = true

```yaml
- in: query
  name: "filters"
  required: false
  explode: false
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