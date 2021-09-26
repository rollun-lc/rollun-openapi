# Опции манифеста

## additionalProperties

[Docs](https://swagger.io/docs/specification/data-models/dictionaries/)

Чтобы указать объект с произвольными полями любых типов, можно использовать опцию `additionalProperties: true`:
```
type: object
additionalProperties: true
```
Теперь в этом объекте может быть любое количество полей с любыми названиями и типами.

Если же мы знаем, что все произвольные поля будут определенного типа, например `string`, то можно его указать:
```
type: object
additionalProperties:
    type: string
```
Чтобы в файлах `.mustache` проверить, задан ли `additionalProperties: true`, можно использовать переменную `isFreeFormObject`.

Список всех доступных переменных в `.mustache` файлах можно посмотреть [тут](https://github.com/OpenAPITools/openapi-generator/blob/master/modules/openapi-generator/src/main/java/org/openapitools/codegen/CodegenProperty.java).