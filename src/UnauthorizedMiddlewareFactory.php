<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

final class UnauthorizedMiddlewareFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, string $requestedName, ?array $options = null): mixed
    {
        $responseAdapter = $container->has(UnauthorizedResponseInterface::class)
            ? $container->get(UnauthorizedResponseInterface::class)
            : null;
        Assert::nullOrIsInstanceOf($responseAdapter, UnauthorizedResponseInterface::class);

        if (null === $responseAdapter) {
            throw new Exception\InvalidConfigurationException(
                'UnauthorizedResponseInterface service is not configured'
            );
        }
        return new UnauthorizedMiddleware($responseAdapter);
    }
}
