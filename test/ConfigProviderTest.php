<?php

declare(strict_types=1);

namespace LmcTest\Authentication;

use Lmc\Authentication\ConfigProvider;
use PHPUnit\Framework\TestCase;

final class ConfigProviderTest extends TestCase
{
    public function testConfigProvider(): void
    {
        $configProvider = new ConfigProvider();
        $this->assertIsArray($configProvider());
        $this->assertArrayHasKey('dependencies', $configProvider());
    }
}
