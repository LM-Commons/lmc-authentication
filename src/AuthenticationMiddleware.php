<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class AuthenticationMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly AuthenticationInterface $authAdapter,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // If already authenticated
        if ($request->getAttribute(UserInterface::class) !== null) {
            return $handler->handle($request);
        }
        $user = $this->authAdapter->authenticate($request);
        return $handler->handle($request->withAttribute(UserInterface::class, $user));
    }
}
