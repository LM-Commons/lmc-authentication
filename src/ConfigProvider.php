<?php

declare(strict_types=1);

namespace Lmc\Authentication;

class ConfigProvider
{
    public function __invoke(): array
    {
        return [
            'dependencies' => $this->getDependencies(),
        ];
    }

    public function getDependencies(): array
    {
        return [
            'factories' => [
                AuthenticationMiddleware::class => AuthenticationMiddlewareFactory::class,
            ],
        ];
    }
}
