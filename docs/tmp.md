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

