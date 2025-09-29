<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @deprecated
 */
interface AuthenticationInterface
{
    public function authenticate(ServerRequestInterface $request): ?UserInterface;
}
