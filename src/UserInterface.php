<?php

declare(strict_types=1);

namespace Lmc\Authentication;

/**
 * @deprecated
 */
interface UserInterface
{
    public function getIdentity(): ?string;

    public function getRoles(): iterable;
}
