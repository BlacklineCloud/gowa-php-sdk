<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Integration;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Exception\RateLimitException;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\RetryMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Psr18Transport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Log\NullLogger;

final class TransportRetryTest extends TestCase
{
    public function testRetriesOn429(): void
    {
        $psr17   = new Psr17Factory();
        $counter = new class () {
            public int $value = 0;
        };
        $client = new class ($counter) implements ClientInterface {
            public function __construct(private object $counter)
            {
            }

            public function sendRequest(RequestInterface $request): \Psr\Http\Message\ResponseInterface
            {
                $this->counter->value++;

                return new Response(429);
            }
        };

        $config    = new ClientConfig('https://api.example.test', 'u', 'p', maxRetries: 1);
        $transport = new Psr18Transport(
            $client,
            $config,
            new NullLogger(),
            new RetryMiddleware($config),
        );

        $this->expectException(RateLimitException::class);
        $transport->sendRequest($psr17->createRequest('GET', 'https://api.example.test'));
        self::assertSame(2, $counter->value);
    }
}
