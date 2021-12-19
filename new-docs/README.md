# OpenAPI Rollun

Этот документ описывает, как мы в компании работаем с OpenAPI
технологией, какие у нас есть инструменты и правила.

## Overview

The OpenAPI Specification - это открытая спецификация. Документацию проэкта можно
посмотреть тут - [OpenAPI Initiative a Linux Foundation Collaborative Project.](https://www.openapis.org/).

Мы используем OpenAPI для:
- того что бы следовать API first approach
- разрабатывать самодокументируемые API
- генерировать шаблонный код (api client, server stubs)

Мы поддерживаем 2 платформы:
- PHP (server + client)
- TypeScript (server + client)

## Contributing

Изменения в спецификацию происходят в 2 этапа:
1. Открыть issue с описанием проблемы, и всем необходимым материалом. [шаблон](./issue-template.md)
2. Открыть pull request с описанием проблемы/ссылкой на issue, и всем необходимым материалом. [шаблон](./pr-template.md)

## General workflow

Для того, что бы разрабоать новый сервер, нужно:
- Создать OpenAPI spec, используя наш модифицированный [Swagger Editor](#swagger-editor-docs)
- Сгенерировать код для сервера с OpenAPI spec (контроллеры, DTOs и тд.) с помощью [generator](#server-stub-generator)
- Добавить [openapi lint](#openapi-lint) в ваш CI/CD pipeline.
- Реализовать методы, в сгенерированом коде.

Для того, что бы начать работу с сервисом через его API, нужно:
- Сгенерировать с OpenAPI spec клиентскую библиотеку с помощью [generator](#client-lib-generator)

## Tools

### Swagger Editor Docs - [doc](./tools/swagger-editor.md)

### OpenAPI lint - [doc](./tools/openapi-lint.md)

### Server stub generator

- PHP - [docs](./server/php.md)
- TypeScript - [docs](./server/ts.md)

### Client Lib generator

- PHP - [docs](./client/php.md)
- TypeScript - [docs](./client/ts.md)

## Quick Start

Гайд с примерами кода/команд, которые нужны для ефективной работы
с OpenAPI в нашей компании [link](./quick-start/README.md)

 ## Cross platform testing

TODO: продумать механизм тестирования разных манифестов, для всех поддерживаемых платформ
