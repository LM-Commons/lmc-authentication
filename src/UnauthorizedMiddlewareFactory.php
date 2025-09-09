<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

final class UnauthorizedMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): UnauthorizedMiddleware
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
