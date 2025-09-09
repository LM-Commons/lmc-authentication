<?php

declare(strict_types=1);

namespace LmcTest\Authentication;

use Lmc\Authentication\Exception\InvalidConfigurationException;
use Lmc\Authentication\UnauthorizedMiddleware;
use Lmc\Authentication\UnauthorizedMiddlewareFactory;
use Lmc\Authentication\UnauthorizedResponseInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Server\MiddlewareInterface;

#[CoversClass(UnauthorizedMiddlewareFactory::class)]
final class UnAuthorizedMiddlewareFactoryTest extends TestCase
{
    /** @var ContainerInterface&MockObject  */
    private ContainerInterface $container;

    /** @var UnauthorizedMiddlewareFactory&MockObject  */
    private UnauthorizedMiddlewareFactory $factory;

    private UnauthorizedResponseInterface $responseAdapter;

    protected function setUp(): void
    {
        parent::setUp();
        $this->container = $this->createMock(ContainerInterface::class);
        /** @psalm-suppress InvalidPropertyAssignmentValue */
        $this->factory         = new UnauthorizedMiddlewareFactory();
        $this->responseAdapter = $this->createMock(UnauthorizedResponseInterface::class);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testInvokeWithResponseAdapter(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with(UnauthorizedResponseInterface::class)
            ->willReturn(true);

        $this->container
            ->expects(self::once())
            ->method('get')
            ->with(UnauthorizedResponseInterface::class)
            ->willReturn($this->responseAdapter);

        /** @var MiddlewareInterface $middleware */
        $middleware = ($this->factory)($this->container, '', []);
        self::assertEquals(new UnauthorizedMiddleware($this->responseAdapter), $middleware);
    }

    /**
     * @throws ContainerExceptionInterface
     */
    public function testInvokeWithInvalidResponseAdapter(): void
    {
        $this->container
            ->expects($this->once())
            ->method('has')
            ->with(UnauthorizedResponseInterface::class)
            ->willReturn(false);

        self::expectException(InvalidConfigurationException::class);
        /** @psalm-suppress UnusedVariable */
        /** @psalm-suppress MixedAssignment */
        $middleware = ($this->factory)($this->container, '', []);
    }
}
