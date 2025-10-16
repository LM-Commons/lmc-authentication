<?php

declare(strict_types=1);

namespace LmcTest\Authentication;

use Lmc\Authentication\UnauthorizedMiddleware;
use Lmc\Authentication\UnauthorizedMiddlewareFactory;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Exception\InvalidConfigException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

#[CoversClass(UnauthorizedMiddlewareFactory::class)]
final class UnAuthorizedMiddlewareFactoryTest extends TestCase
{
    /** @var ContainerInterface&MockObject  */
    private ContainerInterface $container;

    /** @var UnauthorizedMiddlewareFactory&MockObject  */
    private UnauthorizedMiddlewareFactory $factory;

    /** @var AuthenticationInterface&MockObject  */
    private AuthenticationInterface $authAdapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = $this->createMock(ContainerInterface::class);
        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->factory     = new UnauthorizedMiddlewareFactory();
        $this->authAdapter = $this->createMock(AuthenticationInterface::class);
    }

    public function testInvokeWithAuthenticationAdapter(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with(AuthenticationInterface::class)
            ->willReturn(true);

        $this->container
            ->expects(self::once())
            ->method('get')
            ->with(AuthenticationInterface::class)
            ->willReturn($this->authAdapter);

        /** @var MiddlewareInterface $middleware */
        $middleware = ($this->factory)($this->container);
        self::assertEquals(new UnauthorizedMiddleware($this->authAdapter), $middleware);
    }

    public function testInvokeWithInvalidResponseAdapter(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with(AuthenticationInterface::class)
            ->willReturn(false);

        self::expectException(InvalidConfigException::class);

        ($this->factory)($this->container);
    }
}
