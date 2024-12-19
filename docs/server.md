# Реализация серверной части

Информация для тех кто будет реализоваывать серверную часть манифеста.

## Генерация манифеста

Серверный контроллер генерирует примерно такие методы:

```php
public function putById($id, $bodyData)
{
    if (method_exists($this->controllerObject, 'putById')) {
        $bodyDataArray = (array) $bodyData;

        return $this->controllerObject->putById($id, $bodyDataArray);
    }

    throw new \Exception('Not implemented method');
}
```

Проблема в `$bodyDataArray = (array) $bodyData;`, при такой конвертации могут неправильно конвертироваться ключи массива
(отличаться от тех что в манифесте) и конвертируются только ключи верхнего уровня, т.е. если под ключем
$bodyData->receiver находиться объект, то в bodyDataArray['receiver'] тоже будет объект.

Чтобы это исправить нужно выполнять конвертацию через DataTransferObject. Для этого с версии 8.2 в генератор добавлена
опция `arrayConverting`, которая может принимать два значения: base -> для конвертации как в примере выше (через (
array)),
или dataTransfer для конвертации с помощью DataTransferObject. Значение base используется по умолчанию и нужно для
обратной
совместимости, но объеявлено устаревшим. Рекомендованный способ - использовать --arrayConverting=dataTransfer при
генерации.

Пример:

```bash
php bin/openapi-generator generate:server --arrayConverting=dataTransfer
```

## Запись сообщений в messages

Структура ответа принятая в rollun выглядит следующим образом.

```json5
{
  "messages": [
    {
      "level": "error",
      "type": "UNDEFINED",
      "text": "Some message for human."
    }
  ],
  "data": {
    // rest resource
  }
}
```

Под ключом 'data' находится информация про Rest ресурс (или null в случае ошибки), а в 'messages' можно записывать
дополнительную полезную информацию: предупреждения, сообщения об ошибках.

Так как необходимость записать сообщение может возникнуть в любой момент выполнения программы, то был создан специальный
интерфейс [`OpenAPI\Server\Response\MessageWriterInterface`](../src/OpenAPI/Server/Response/MessageWriterInterface.php)
с
помощью которого это можно делать.

Достаточно просто указать этот интерфейс как один из параметров конструктора. По умолчанию реализацией этого интерфейса
является объект [`OpenAPI\Server\Response\MessageCollector`](../src/OpenAPI/Server/Response/MessageCollector.php),
который
будет записывать все сообщения себе в массив. Этот объект так же имплементирует интерфейс
[`OpenAPI\Server\Response\MessageReaderInterface`](../src/OpenAPI/Server/Response/MessageReaderInterface.php), с помощью
которого считываются все сообщения и добавляются к ответу.

Пример:
Допустим у нас есть класс, который закупает товары у поставщика. Но какой-то из товаров не смог закупиться из-за того
что его не было в наличии. И мы хотим написать об это в ответ в секцию messages.

```php
use OpenAPI\Server\Response\MessageWriterInterface;

class Buyer {
    /**
     * @var MessageWriterInterface
     */
    private $messageWriter;
    
    public function __construct(MessageWriterInterface $messageWriter) {
       $this->messageWriter = $messageWriter;
    }
     
    public function buy(array $requestedItems) {
       $buyedItems = [];
       
       // ... buying items
       
       if (count($buyedItems) < count($requestedItems)) {
           $this->messageWriter->alert('Some items not available.', 'ITEMS_NOT_AVAILABLE');
       }
    }
}
```

в итоге в ответе мы получим

```json5
{
  "messages": [
    // other messages,
    {
      "level": "alert",
      "type": "ITEMS_NOT_AVAILABLE",
      "text": "Some items not available."
    }
  ],
  "data": {
    // rest resource
  }
}
```

## Конфигурация контроллера rest объектов (controller object)

С версии 8 в константу CONTROLLER_OBJECT при генерации пишется строковый ключ, по которому можно добавить конфигурацию в
DI контейнер.
Название ключа формируется по схеме [TAG] + [VERSION] + 'Controller'. Например, для тега Order с версией API 1 будет
генерироваться ключ `Order1Controller`

Также в версии 8 удалено указание возвращаемого значения из методов классов обработчиков, поэтому сейчас можно
возвращать из контроллеров как массивы,
так и готовые DTO обьекты. В обоих случаях перед формированием HTTP ответа возвращаемые значения будут провалидированы.

## Як працює роутинг

Для роботи бібліотеки необхідно використовувати роутер від Articus (він має назву FastRouter, але це не той самий
FastRouter що від Mezzio). Він прописаний як аліас на RouterInterface в конфігах бібліотеки rollun-openapi:
rollun-openapi/src/OpenAPI/Config/PathHandlerConfig.php at 3d3ba85b82dffd5f1d1e0f38112cf69d8662a6c3 ·
rollun-lc/rollun-openapi . Тому достатньо просто підключити ConfigProvider собі в бібліотеку.

Цей роутер працює на основі анотацій, що прописані в згенерованих класах. 