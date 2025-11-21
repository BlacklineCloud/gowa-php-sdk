<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Http;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Exception\AuthenticationException;
use BlacklineCloud\SDK\GowaPHP\Exception\RateLimitException;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\AuthMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\CorrelationIdMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\IdempotencyMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\RetryMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Psr18Transport;
use BlacklineCloud\SDK\GowaPHP\Support\NativeUuidGenerator;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\NullLogger;

final class Psr18TransportTest extends TestCase
{
    public function testRetryOn429ThrowsRateLimit(): void
    {
        $client = $this->mockClient(429);
        $config = new ClientConfig('https://api.example.test', 'u', 'p');
        $transport = new Psr18Transport(
            $client,
            $config,
            new NullLogger(),
            new AuthMiddleware($config),
            new IdempotencyMiddleware(new NativeUuidGenerator()),
            new CorrelationIdMiddleware(new NativeUuidGenerator()),
            new RetryMiddleware($config),
        );

        $this->expectException(RateLimitException::class);
        $transport->sendRequest($this->dummyRequest());
    }

    public function test401ThrowsAuthException(): void
    {
        $client = $this->mockClient(401);
        $config = new ClientConfig('https://api.example.test', 'u', 'p');
        $transport = new Psr18Transport($client, $config, new NullLogger());

        $this->expectException(AuthenticationException::class);
        $transport->sendRequest($this->dummyRequest());
    }

    private function mockClient(int $status): ClientInterface
    {
        return new class($status) implements ClientInterface {
            public function __construct(private int $status)
            {
            }

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                return new Response($this->status);
            }
        };
    }

    private function dummyRequest(): RequestInterface
    {
        return new Request('GET', 'https://example.test/');
    }
}
