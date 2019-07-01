# Simple Zend Framework 3 Rest Api

## Introduction

This is a basic  rest application using the Zend Framework 3 MVC layer and module
systems. 
## Installation using Docker


Build Container:

```bash
$ docker-compose up -d
```
Get the lastest version of dependecies:
```bash
$ docker-compose exec app composer update
```

Run Database Migrations
```bash
$ docker-compose exec app ./vendor/bin/doctrine-module --no-interaction migrations:migrate
```

Test Endpoints
```bash
$ docker-compose exec app ./vendor/bin/phpunit module/Rest/test
```
### Postman Collection
> Zend-Rest.postman_collection.json

### Swagger Document
> http://localhost:8080/swagger/index.html


