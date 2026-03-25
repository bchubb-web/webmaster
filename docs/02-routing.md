# 02 - Routing

## Introduction

Webmaster's routing system is built on top of the popular Symfony Routing component, providing a flexible and powerful way to define and manage routes in your application.

## Targets
a 'Target' is what is executed when a given route is dispatched, webmaster supports several types of targets:
- Controller actions
- Invokable classes
- Request handlers
- plain php files

### Controller actions
first, what is a controller? A controller is a php class which has public methods which handle requests, these are nicknamed "actions".

controllers can be routed to by specifying the controller and action name in an array

```php
$router->add(
    uri: '/profile/edit',
    target: [ProfileController::class, 'edit'],
    methods: ['GET', 'POST'],
    name: 'profile.edit'
);
```

### Invokable classes
an invokable class is a class which implements the `__invoke` magic method, this method is called when the class is treated as a function.

### Request handlers
a request handler is a class which implements the `Webmaster\Http\RequestHandlerInterface`, which has a single method `handle(Psr\Http\Message\ServerRequestInterface $request): Psr\Http\Message\ResponseInterface`.

### Plain PHP files
by specifying a path to a php file as the target of a route, webmaster will include that file when the route is matched. The file will have access to a `$request` variable which is an instance of `Psr\Http\Message\ServerRequestInterface`, and should return an instance of `Psr\Http\Message\ResponseInterface`.

