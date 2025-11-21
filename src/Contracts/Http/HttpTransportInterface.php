<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Contracts\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Abstraction over HTTP client to keep SDK independent from concrete implementations.
 * Implementations must be safe for concurrent usage and inject all dependencies via constructor.
 */
interface HttpTransportInterface
{
    /**
     * Send PSR-7 request and return PSR-7 response.
     * Implementations should handle low-level transport concerns (timeouts, DNS, TLS) and MUST NOT mutate request.
     *
     * @throws \RuntimeException When a non-recoverable transport error occurs.
     */
    public function sendRequest(RequestInterface $request): ResponseInterface;
}
