<?php

declare(strict_types=1);

namespace LmcTest\Authentication;

use Lmc\Authentication\AuthenticationInterface;
use Lmc\Authentication\AuthenticationMiddleware;
use Lmc\Authentication\AuthenticationMiddlewareFactory;
use Lmc\Authentication\Exception\InvalidConfigurationException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

#[CoversClass(AuthenticationMiddlewareFactory::class)]
final class AuthenticationMiddlewareFactoryTest extends TestCase
{
    /** @var ContainerInterface&MockObject  */
    private ContainerInterface $container;

    /** @var AuthenticationMiddlewareFactory&MockObject  */
    private AuthenticationMiddlewareFactory $factory;

    private AuthenticationInterface $authentication;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = $this->createMock(ContainerInterface::class);
        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->factory        = new AuthenticationMiddlewareFactory();
        $this->authentication = $this->createMock(AuthenticationInterface::class);
    }

    public function testInvokeWithAuthenticationService(): void
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
            ->willReturn($this->authentication);

        $middleware = ($this->factory)($this->container);
        self::assertEquals(new AuthenticationMiddleware($this->authentication), $middleware);
    }

    public function testInvokeWithInvalidAuthenticationService(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with(AuthenticationInterface::class)
            ->willReturn(false);

        self::expectException(InvalidConfigurationException::class);

        ($this->factory)($this->container);
    }
}
