# Правила создание openapi манифеста

## манифест
1 modulName - 1 манифест Где лежит манифест public/api/docs/modulName/v1/openApi.yml
modulName - имя манифеста
groupTaskname - tag - действует в пределах одного манифеста (возможно несколько: RM, PU, Autodist)
tasknameOne - endPoint
v1 - версия


## Структура URL
microserviceName.com/openapi/modulName/v1/groupTaskname/[tasknameOne]


## Структура namespace
### Serever
microserviceName/src/modulName/src/openApi/server/v1/groupTaskname/handlers/TasknameOne.php -> get()


## PHP

v1-?

microserviceName - microserviceName       Catalog
modulName - modulName                     Supliers
groupTaskname - className                 RM, PU, Autodist
tasknameOne - className->function         RM->getPrice(id)





имя манифеста, место хранения манифеста, tag в манифесте,  groupTaskname, tasknameOne,  modulName,  microserviceName


### Client
microserviceName/src/openApi/client/taskname/v1/

1. Прежде всего ознакомьтесь с документацией swagger (openapi). Для этого перейдите по [ссылке](https://swagger.io/docs/).   
2. Нужно использовать исключительно версию openapi **3.0.0**
3. **title** нужно указывать обязательно. Это должно быть одно слово (camelcase). Это поле используется генератором для создания каталога и namespace.
   ![title](assets/img/1.png)  
4. **version** нужно указывать обязательно. Версия нужно дублировать с версией в пути. Это поле используется генератором для создания каталога и namespace, для того чтобы можно было изолировать версии api.
   ![version](assets/img/2.png)
5. **tags** нужно указывать обязательно. Теги используются для группирования методов. Генератор создаст столько **handlers** сколько **tags**.
   ![tag](assets/img/3.png)   
6. **operationId** нужно указывать обязательно. Генератор создаст метод в **handler** с таким же именем. 
   ![operationId](assets/img/4.png) 
7. Очень важно в качество возвращаемых данных **всегда** указывать модель. Дело в том, что все методы которые что-либо возвращают, возвращают DTO объект. В случае если DTO объект не будет создан вами, генератор создаст его сам, например, Response200. А вот, если вы опишете модели сами и в манифесте будете только ссылаться на них, то генератор создаст DTO объекты с ваших моделей и не будет добавлять свои "непонятные" объекты.
   ![dto](assets/img/5.png)
