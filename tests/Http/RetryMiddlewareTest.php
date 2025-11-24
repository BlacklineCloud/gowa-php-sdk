<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Http;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\RetryMiddleware;
use Nyholm\Psr7\Request;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class RetryMiddlewareTest extends TestCase
{
    public function testRetriesOnServerErrorThenSucceeds(): void
    {
        $config     = new ClientConfig('https://api.example.test', 'u', 'p', maxRetries: 1);
        $middleware = new RetryMiddleware($config);
        $request    = new Request('GET', 'https://api.example.test');

        $callCount = 0;
        $next      = function () use (&$callCount) {
            $callCount++;
            if ($callCount === 1) {
                return new Response(500);
            }

            return new Response(200);
        };

        $response = $middleware->handle($request, $next);

        self::assertSame(2, $callCount);
        self::assertSame(200, $response->getStatusCode());
    }

    public function testRetriesRateLimitThenSucceeds(): void
    {
        $config     = new ClientConfig('https://api.example.test', 'u', 'p', maxRetries: 1);
        $middleware = new RetryMiddleware($config);
        $request    = new Request('GET', 'https://api.example.test');

        $callCount = 0;
        $next      = function () use (&$callCount) {
            $callCount++;
            if ($callCount === 1) {
                throw new \BlacklineCloud\SDK\GowaPHP\Exception\RateLimitException(retryAfterSeconds: 1);
            }

            return new Response(200);
        };

        $response = $middleware->handle($request, $next);

        self::assertSame(2, $callCount);
        self::assertSame(200, $response->getStatusCode());
    }
}
