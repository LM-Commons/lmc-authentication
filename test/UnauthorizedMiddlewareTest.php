<?php

declare(strict_types=1);

namespace LmcTest\Authentication;

use Lmc\Authentication\UnauthorizedMiddleware;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\UserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(UnauthorizedMiddleware::class)]
final class UnauthorizedMiddlewareTest extends TestCase
{
    /** @var ServerRequestInterface&MockObject */
    private ServerRequestInterface $request;

    /** @var RequestHandlerInterface&MockObject */
    private RequestHandlerInterface $handler;

    /** @var AuthenticationInterface&MockObject  */
    private AuthenticationInterface $authAdapter;

    private UnauthorizedMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authAdapter = $this->createMock(AuthenticationInterface::class);
        $this->request     = $this->createMock(ServerRequestInterface::class);
        $this->handler     = $this->createMock(RequestHandlerInterface::class);

        $this->middleware = new UnauthorizedMiddleware($this->authAdapter);
    }

    public function testProcessWithUser(): void
    {
        $user = $this->createMock(UserInterface::class);

        $response = $this->createMock(ResponseInterface::class);

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with(UserInterface::class)
            ->willReturn($user);

        $this->handler
            ->expects($this->once())
            ->method('handle')
            ->with($this->request)
            ->willReturn($response);

        self::assertSame($response, $this->middleware->process($this->request, $this->handler));
    }

    public function testProcessWithoutUser(): void
    {
        $response = $this->createMock(ResponseInterface::class);

        $this->request
            ->expects($this->once())
            ->method('getAttribute')
            ->with(UserInterface::class)
            ->willReturn(null);

        $this->authAdapter
            ->expects($this->once())
            ->method('unauthorizedResponse')
            ->with($this->request)
            ->willReturn($response);

        self::assertSame($response, $this->middleware->process($this->request, $this->handler));
    }
}
