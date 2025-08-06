<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Laminas\ServiceManager\Factory\FactoryInterface;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

class UnauthorizedMiddlewareFactory implements FactoryInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null): mixed
    {
        $respopnseAdapter = $container->has(UnauthorizedResponseInterface::class)
            ? $container->get(UnauthorizedResponseInterface::class)
            : null;
        Assert::nullOrIsInstanceOf($respopnseAdapter, UnauthorizedResponseInterface::class);

        if (null === $respopnseAdapter) {
            throw new Exception\InvalidConfigurationException(
                'UnauthorizedResponseInterface service is not configured'
            );
        }
        return new UnauthorizedMiddleware($respopnseAdapter);
    }
}
