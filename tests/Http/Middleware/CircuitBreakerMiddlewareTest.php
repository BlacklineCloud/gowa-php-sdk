<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Http\Middleware;

use BlacklineCloud\SDK\GowaPHP\Http\Middleware\CircuitBreakerMiddleware;
use BlacklineCloud\SDK\GowaPHP\Support\CircuitBreakerInterface;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class CircuitBreakerMiddlewareTest extends TestCase
{
    public function testDelegatesThroughBreaker(): void
    {
        $psr17   = new Psr17Factory();
        $request = $psr17->createRequest('GET', 'https://example.test/foo');

        $breaker = new class () implements CircuitBreakerInterface {
            public bool $called = false;
            public function call(string $key, callable $action): mixed
            {
                $this->called = true;
                return $action();
            }
        };

        $mw       = new CircuitBreakerMiddleware($breaker);
        $response = $mw->handle($request, static fn () => new Response(200));

        self::assertTrue($breaker->called);
        self::assertSame(200, $response->getStatusCode());
    }
}
