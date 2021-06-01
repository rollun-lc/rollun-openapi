# Реализация серверной части

Информация для тех кто будет реализоваывать серверную часть манифеста.

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
интерфейс [`OpenAPI\Server\Response\MessageWriterInterface`](../src/OpenAPI/Server/Response/MessageWriterInterface.php) с
помощью которого это можно делать.

Достаточно просто указать этот интерфейс как один из параметров конструктора. По умолчанию реализацией этого интерфейса
является объект [`OpenAPI\Server\Response\MessageCollector`](../src/OpenAPI/Server/Response/MessageCollector.php), который
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

###Информация по контроллерам rest обьектов (controller object)
С версии 8 в константу CONTROLLER_OBJECT при генерации пишется ключ, по которому можно добавить конфигурацию в DI контейнер.
Также удалено указание возвращаемого значения из методов классов обработчиков, поэтому сейчас можно возвращать из контроллеров как массивы,
так и готовые DTO обьекты. В обоих случаях перед формированием ответа возвращаемые значения будут провалидированы.