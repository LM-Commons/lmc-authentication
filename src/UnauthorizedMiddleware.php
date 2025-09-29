<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Mezzio\Authentication\AuthenticationInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final readonly class UnauthorizedMiddleware implements MiddlewareInterface
{
    public function __construct(
        private AuthenticationInterface $authAdapter
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (null !== $request->getAttribute(UserInterface::class)) {
            return $handler->handle($request);
        }
        return $this->authAdapter->unauthorizedResponse($request);
    }
}
