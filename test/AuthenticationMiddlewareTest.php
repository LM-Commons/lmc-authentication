<?php

declare(strict_types=1);

namespace LmcTest\Authentication;

use Lmc\Authentication\AuthenticationMiddleware;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(AuthenticationMiddleware::class)]
final class AuthenticationMiddlewareTest extends TestCase
{
    /** @var AuthenticationInterface&MockObject  */
    private AuthenticationInterface $authentication;

    /** @var ServerRequestInterface&MockObject */
    private ServerRequestInterface $request;

    /** @var RequestHandlerInterface&MockObject */
    private RequestHandlerInterface $handler;

    private AuthenticationMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authentication = $this->createMock(AuthenticationInterface::class);
        $this->request        = $this->createMock(ServerRequestInterface::class);
        $this->handler        = $this->createMock(RequestHandlerInterface::class);

        $this->middleware = new AuthenticationMiddleware($this->authentication);
    }

    public function testProcess(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $user = $this->createMock(UserInterface::class);

        $this->authentication
            ->expects($this->once())
            ->method('authenticate')
            ->with($this->request)
            ->willReturn($user);

        $this->request
            ->expects($this->once())
            ->method('withAttribute')
            ->with(UserInterface::class)
            ->willReturn($this->request);

        $this->handler
            ->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($response);

        self::AssertSame($response, $this->middleware->process($this->request, $this->handler));
    }
}
