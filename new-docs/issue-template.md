# (название проблемы)

1. Описание проблемы

Описание: TODO

2. Пример манифеста, который не работает

Шаблон манифеста - 
```yaml
openapi: 3.0.0
info:
  version: "1"
  title: Sample
  description: Sample manifest to showcase issue
servers:
  - url: http://server.dev/openapi/Sample/v1
paths:
  /{namespace}/Entity:
    get:
      parameters:
        - in: query
          name: "status"
          required: false
          schema:
            type: string
        - in: path
          name: "namespace"
          required: true
          schema:
            type: string
      responses:
        '200':
          description: entity
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/EntityResult'
        '400':
          description: Bad Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResult'
        '500':
          description: Internal error
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResult'
    post:
      parameters:
        - in: path
          name: "namespace"
          required: true
          schema:
            type: string
      requestBody:
        description: ""
        required: true
        content:
          application/json:
            schema:
              $ref: '#/components/schemas/Entity'
      responses:
        '201':
          description: entity
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/EntityResult'
        '400':
          description: Bad Request
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResult'
        '500':
          description: Internal error
          content:
            application/json:
              schema:
                $ref: '#/components/schemas/ErrorResult'
components:
  schemas:
    Message:
      type: object
      required:
        - level
        - text
      properties:
        level:
          type: string
          description: 'Message level  (like in a logger)'
          example: 'error'
        text:
          type: string
          description: 'Message text'
          example: 'Something wrong.'
        context:
          type: array
          description: 'Message context (like in a logger)'
          items:
            type: string
    ErrorResult:
      type: object
      properties:
        messages:
          type: array
          items:
            $ref: '#/components/schemas/Message'
    Result:
      allOf:
        - $ref: '#/components/schemas/ErrorResult'
      type: object
      properties:
        data:
          type: object
    EntityResult:
      allOf:
        - $ref: '#/components/schemas/Result'
      type: object
      properties:
        data:
          $ref: '#/components/schemas/Entity'
    Entity:
      type: object
      required:
        - id
        - name
      properties:
        id:
          type: string
          example: '5f51f78ccaa4c'
```

