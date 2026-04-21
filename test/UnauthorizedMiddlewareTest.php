<?php

declare(strict_types=1);

namespace LmcTest\Authentication;

use Lmc\Authentication\UnauthorizedMiddleware;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(UnauthorizedMiddleware::class)]
final class UnauthorizedMiddlewareTest extends TestCase
{
    public function testProcessWithUser(): void
    {
        $user = $this->createStub(UserInterface::class);

        $response = $this->createStub(ResponseInterface::class);

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with(UserInterface::class)
            ->willReturn($user);

        $handler = $this->createMock(RequestHandlerInterface::class);

        $handler->expects($this->once())
            ->method('handle')
            ->with($request)
            ->willReturn($response);

        $authAdapter = $this->createStub(AuthenticationInterface::class);

        $middleware = new UnauthorizedMiddleware($authAdapter);

        self::assertSame($response, $middleware->process($request, $handler));
    }

    public function testProcessWithoutUser(): void
    {
        $response = $this->createStub(ResponseInterface::class);

        $request = $this->createMock(ServerRequestInterface::class);

        $request->expects($this->once())
            ->method('getAttribute')
            ->with(UserInterface::class)
            ->willReturn(null);

        $authAdapter = $this->createMock(AuthenticationInterface::class);

        $authAdapter->expects($this->once())
            ->method('unauthorizedResponse')
            ->with($request)
            ->willReturn($response);

        $handler = $this->createStub(RequestHandlerInterface::class);

        $middleware = new UnauthorizedMiddleware($authAdapter);

        self::assertSame($response, $middleware->process($request, $handler));
    }
}
