<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http\Middleware;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\MiddlewareInterface;
use BlacklineCloud\SDK\GowaPHP\Exception\RateLimitException;
use BlacklineCloud\SDK\GowaPHP\Exception\TransportException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class RetryMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ClientConfig $config)
    {
    }

    public function handle(RequestInterface $request, callable $next): ResponseInterface
    {
        $attempt = 0;
        $delay = 0;

        while (true) {
            if ($delay > 0) {
                usleep($delay * 1000);
            }

            try {
                $response = $next($request);

                if ($this->isRetryableStatus($response->getStatusCode()) && $attempt < $this->config->maxRetries) {
                    $delay = $this->nextDelayMs(++$attempt);
                    continue;
                }

                return $response;
            } catch (RateLimitException $e) {
                if ($attempt >= $this->config->maxRetries) {
                    throw $e;
                }

                $attempt++;
                $delay = $this->retryAfterMs($e->retryAfterSeconds) ?? $this->nextDelayMs($attempt);
                continue;
            } catch (\Throwable $e) {
                if ($attempt >= $this->config->maxRetries) {
                    throw new TransportException($e->getMessage(), previous: $e);
                }

                $attempt++;
                $delay = $this->nextDelayMs($attempt);
                continue;
            }
        }
    }

    private function isRetryableStatus(int $status): bool
    {
        return \in_array($status, [429, 502, 503, 504], true);
    }

    private function nextDelayMs(int $attempt): int
    {
        return (int) (100 * ($this->config->retryBackoffFactor ** ($attempt - 1)));
    }

    private function retryAfterMs(?int $retryAfterSeconds): ?int
    {
        return $retryAfterSeconds !== null ? $retryAfterSeconds * 1000 : null;
    }
}
