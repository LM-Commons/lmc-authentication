---
title: Usage
sidebar_position: 2
---

This component provides authentication abstraction using a middleware approach 
for PSR-7 and PSR-15 applications.

It uses the same principles as the [Mezzio Authentication](https://docs.mezzio.dev/mezzio-authentication/) component
and supports any authentication adapter that implements the 
`Mezzio\Authentication\AuthenticationInterface`.

The main difference from the Mezzio Authentication component is that the authentication middleware
will simply try to authenticate the request without taking any further action based on the authenticated result.

The authenticated result or `null` if not authenticated, will be passed to the next middleware using 
a `UserInterface` request attribute.

### Why use this component versus Mezzio Authentication?

The Mezzio Authentication middleware, when it is unable to authenticate the request, will call the
authentication adapter's `unauthorizedResponse()` method.

Lmc Authentication splits these two steps into two separate middleware:

- `AutnenticationMiddleware` will use the authentication adapter to authenticate the request
and execute the next middleware by passing the authenticated user or `null` in the `UserInterface` attribute.
- `UnauthorizedMiddleware` will check for the `UserInterface` request attribute. If the `UserInterface` attribute
is `null`, the middleware will return the response composed by the adapter's `unauthorizedResponse()` method.
Otherwise, the middleware will execute the next middleware.

This provides the flexibility to take specific action based on whether the request is authenticated or not, such as
using route guards middleware to handle non-authenticated requests.

### Usage in pipelines and routes

As an example, the `AuthenticationMiddleware` can be used early in a pipeline to perform authentication:

```php
// in config/pipeline.php
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
{
    /* other pipelines ... */
    
    $app->pipe(AuthenticationMiddleware::class);
    
    $app->pipe(MyRouteGuardMiddleware::class);
    
    /* other pipelines ... */
};
```
If a specific route should only execute when the request is authenticated, the `UauthorizedMiddleware`
middleware can be used to only allow authenticated requests:

```php
// in config/routes.php
return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
{
    /* other handlers  ... */
    
    $app->get(
        `/home`,
        [
           \Lmc\Authentication\UnauthorizedMiddleware::class,
           HomeHandler::class,
        ],
        'home',
    );
   
    /* other handlers ... */
};
```





