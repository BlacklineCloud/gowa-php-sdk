<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Config;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfigBuilder;
use PHPUnit\Framework\TestCase;

final class ClientConfigBuilderTest extends TestCase
{
    public function testBuildsFromArray(): void
    {
        $config = ClientConfigBuilder::fromArray([
            'base_uri' => 'https://api.example.test',
            'username' => 'user',
            'password' => 'pass',
            'base_path' => '/gowa',
            'request_timeout_ms' => '5000',
            'max_retries' => '5',
        ]);

        self::assertSame('https://api.example.test', $config->baseUri);
        self::assertSame(5, $config->maxRetries);
    }
}
