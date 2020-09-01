## манифест
moduleName(title) - имя манифеста
groupTaskName(tag) - действует в пределах одного манифеста (возможно несколько: RM, PU, Autodist) 
v1 - версия

## Структура URL
microserviceName.com/openapi/moduleName/v1/groupTaskName[?{query}] POST | GET(query) | DELETE(query) | PATCH(query)
microserviceName.com/openapi/moduleName/v1/groupTaskName/{id} GET | DELETE | PUT | PATCH

## Структура namespace
### Server
microserviceName/src/moduleName/src/openApi/server/v1/handler/groupTaskName.php
microserviceName/src/moduleName/src/openApi/server/v1/handler/groupTaskNameId.php
### Client
microserviceName/src/moduleName/src/openApi/client/v1/api/groupTaskNameApi.php


## PHP

### Handler methods
post($data)
get($query)
delete($query)
patch($query, $data)
getById($id)
deleteById($id)
putById($id, $data)
patchById($id, $data)
