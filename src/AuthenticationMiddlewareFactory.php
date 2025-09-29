<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\Exception\InvalidConfigException;
use Psr\Container\ContainerInterface;
use Webmozart\Assert\Assert;

final class AuthenticationMiddlewareFactory
{
    public function __invoke(ContainerInterface $container): AuthenticationMiddleware
    {
        $authAdapter = $container->has(AuthenticationInterface::class)
            ? $container->get(AuthenticationInterface::class)
            : null;
        Assert::nullOrIsInstanceOf($authAdapter, AuthenticationInterface::class);

        if (null === $authAdapter) {
            throw new InvalidConfigException(
                'AuthenticationInterface service is not configured'
            );
        }
        return new AuthenticationMiddleware($authAdapter);
    }
}
