<?php

namespace Lmc\Authentication;

interface UserInterface
{
    public function getIdentity(): ?string;

    public function getRoles(): iterable;
}
