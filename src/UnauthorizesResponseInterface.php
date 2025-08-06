<?php

declare(strict_types=1);

namespace Lmc\Authentication;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface UnauthorizesResponseInterface
{
    public function unauthorizedResponse(ServerRequestInterface $request): ResponseInterface;
}
