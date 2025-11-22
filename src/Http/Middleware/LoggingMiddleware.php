<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http\Middleware;

use BlacklineCloud\SDK\GowaPHP\Contracts\Http\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

final class LoggingMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function handle(RequestInterface $request, callable $next): ResponseInterface
    {
        $start      = microtime(true);
        $response   = $next($request);
        $elapsed    = microtime(true) - $start;
        $durationMs = (int) round($elapsed * 1000.0);

        $this->logger->info('HTTP request', [
            'method'          => $request->getMethod(),
            'uri'             => (string) $request->getUri(),
            'status'          => $response->getStatusCode(),
            'duration_ms'     => $durationMs,
            'correlation_id'  => $request->getHeaderLine('X-Correlation-ID'),
            'idempotency_key' => $request->getHeaderLine('Idempotency-Key'),
        ]);

        return $response;
    }
}
