<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http\Middleware;

use BlacklineCloud\SDK\GowaPHP\Contracts\Http\MiddlewareInterface;
use BlacklineCloud\SDK\GowaPHP\Support\CircuitBreakerInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class CircuitBreakerMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly CircuitBreakerInterface $breaker)
    {
    }

    public function handle(RequestInterface $request, callable $next): ResponseInterface
    {
        $key = $request->getMethod() . ' ' . (string) $request->getUri();

        $response = $this->breaker->call($key, static fn () => $next($request));
        if (!$response instanceof ResponseInterface) {
            throw new \RuntimeException('Circuit breaker returned non-response value');
        }

        return $response;
    }
}
