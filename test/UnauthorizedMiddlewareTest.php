<?php

declare(strict_types=1);

namespace LmcTest\Authentication;

use Lmc\Authentication\UnauthorizedMiddleware;
use Lmc\Authentication\UnauthorizedResponseInterface;
use Lmc\Authentication\UserInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

#[CoversClass(UnauthorizedMiddleware::class)]
final class UnauthorizedMiddlewareTest extends TestCase
{
    /** @var UnauthorizedResponseInterface&MockObject $responseAdapter  */
    private UnauthorizedResponseInterface $responseAdapter;

    /** @var ServerRequestInterface&MockObject */
    private ServerRequestInterface $request;

    /** @var RequestHandlerInterface&MockObject */
    private RequestHandlerInterface $handler;

    private UnauthorizedMiddleware $middleware;

    protected function setUp(): void
    {
        parent::setUp();
        $this->responseAdapter = $this->createMock(UnauthorizedResponseInterface::class);
        $this->request         = $this->createMock(ServerRequestInterface::class);
        $this->handler         = $this->createMock(RequestHandlerInterface::class);

        $this->middleware = new UnauthorizedMiddleware($this->responseAdapter);
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

        $this->responseAdapter
            ->expects($this->once())
            ->method('unauthorizedResponse')
            ->with($this->request)
            ->willReturn($response);

        self::assertSame($response, $this->middleware->process($this->request, $this->handler));
    }
}
