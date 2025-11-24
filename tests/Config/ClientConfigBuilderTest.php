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
            'base_uri'           => 'https://api.example.test',
            'username'           => 'user',
            'password'           => 'pass',
            'base_path'          => '/gowa',
            'request_timeout_ms' => '5000',
            'max_retries'        => '5',
        ]);

        self::assertSame('https://api.example.test', $config->baseUri);
        self::assertSame(5, $config->maxRetries);
    }

    public function testMissingKeyThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        ClientConfigBuilder::fromArray(['base_uri' => 'https://api.example.test']);
    }

    public function testBuildsFromEnv(): void
    {
        putenv('GOWA_BASE_URI=https://env.example.test');
        putenv('GOWA_USERNAME=user');
        putenv('GOWA_PASSWORD=pass');
        $config = ClientConfigBuilder::fromEnv();

        self::assertSame('https://env.example.test', $config->baseUri);
        self::assertSame('user', $config->username);

        putenv('GOWA_BASE_URI');
        putenv('GOWA_USERNAME');
        putenv('GOWA_PASSWORD');
    }
}
