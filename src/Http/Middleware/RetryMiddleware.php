<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http\Middleware;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\MiddlewareInterface;
use BlacklineCloud\SDK\GowaPHP\Exception\AuthenticationException;
use BlacklineCloud\SDK\GowaPHP\Exception\RateLimitException;
use BlacklineCloud\SDK\GowaPHP\Exception\ServerException;
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
        $delay   = 0;

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
            } catch (AuthenticationException $e) {
                // Auth errors are not retryable
                throw $e;
            } catch (RateLimitException $e) {
                if ($attempt >= $this->config->maxRetries) {
                    throw $e;
                }

                $attempt++;
                $delay = $this->retryAfterMs($e->retryAfterSeconds) ?? $this->nextDelayMs($attempt);
                continue;
            } catch (ServerException $e) {
                if ($this->isRetryableStatus($e->statusCode ?? 0) && $attempt < $this->config->maxRetries) {
                    $delay = $this->nextDelayMs(++$attempt);
                    continue;
                }

                throw $e;
            } catch (TransportException $e) {
                if ($attempt >= $this->config->maxRetries) {
                    throw $e;
                }

                $attempt++;
                $delay = $this->nextDelayMs($attempt);
                continue;
            } catch (\Throwable $e) {
                // Unknown exception type, do not obscure cause
                throw $e;
            }
        }
    }

    private function isRetryableStatus(int $status): bool
    {
        return \in_array($status, [500, 502, 503, 504], true);
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
