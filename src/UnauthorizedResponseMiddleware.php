<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

readonly class UnauthorizedResponseMiddleware implements MiddlewareInterface
{
    public function __construct(
        private UnauthorizesResponseInterface $responseAdapter
    ) {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (null !== $request->getAttribute(UserInterface::class)) {
            return $handler->handle($request);
        }
        return $this->responseAdapter->unauthorizedResponse($request);
    }
}
