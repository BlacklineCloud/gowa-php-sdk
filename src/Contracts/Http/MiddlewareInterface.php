<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Contracts\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HTTP middleware contract (PSR-inspired but transport-agnostic) for cross-cutting concerns.
 */
interface MiddlewareInterface
{
    /**
     * @param RequestInterface $request Incoming request
     * @param callable(RequestInterface): ResponseInterface $next Next middleware or transport sender
     */
    public function handle(RequestInterface $request, callable $next): ResponseInterface;
}
