<?php

namespace Lmc\Authentication;

use Psr\Http\Message\ServerRequestInterface;

interface AuthenticationInterface
{
    public function authenticate(ServerRequestInterface $request): ?UserInterface;
}
