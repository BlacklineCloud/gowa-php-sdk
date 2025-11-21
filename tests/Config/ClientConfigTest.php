<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Config;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use PHPUnit\Framework\TestCase;

final class ClientConfigTest extends TestCase
{
    public function testItValidatesBasics(): void
    {
        $config = new ClientConfig('https://api.example.test', 'user', 'pass');

        self::assertSame('https://api.example.test', $config->baseUri);
        self::assertSame('user', $config->username);
        self::assertSame('pass', $config->password);
    }

    public function testWithCreatesNewInstance(): void
    {
        $config = new ClientConfig('https://api.example.test', 'user', 'pass');
        $updated = $config->with(['maxRetries' => 5]);

        self::assertSame(2, $config->maxRetries);
        self::assertSame(5, $updated->maxRetries);
        self::assertNotSame($config, $updated);
    }

    public function testEmptyBaseUriFails(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ClientConfig('', 'u', 'p');
    }
}
