# OpenAPI Rollun

This document describes how to work with OpenAPI in our company -
how to write manifests, generate code for both server side and client side.

## Overview

The OpenAPI Specification is a community-driven open specification within
the [OpenAPI Initiative](https://www.openapis.org/), a Linux Foundation Collaborative Project.

We use OpenAPI to:
- follow API first approach
- make self documented APIs
- generate boilerplate code (api client, server stubs)

Right now we support 3 platforms:
- PHP (server + client)
- TypeScript (server + client)

## General workflow

If there is a need for new service, workflow will be:
- Create OpenAPI spec, using our custom **(LINK TO SWAGGER EDITOR DOCS)**
- Generate code for server from OpenAPI spec (controllers, DTOs etc) **(LINK TO PHP/TS SERVER CODE GEN DOCS)**
- Add openapi lint to your CI/CD pipeline. **(LINK TO OPENAPI LINT DOCS)**
- Implement methods in service

If you want to consume new service via defined OpenAPI:
- Generate client from OpenAPI spec **(LINK TO PHP/TS OPENAPI CLIENT CODE GEN)**

## Tools

### [Swagger Editor Docs](./tools/swagger-editor.md)

### Server stub generator

- PHP - [docs](./server/php.md)
- TypeScript - [docs](./server/ts.md)

### Client Lib generator

- PHP - [docs](./client/php.md)
- TypeScript - [docs](./client/ts.md)

## Demo

**TODO: link to demo repository with examples of OpenAPI specs + generated servers/clients with instructions for each language**
