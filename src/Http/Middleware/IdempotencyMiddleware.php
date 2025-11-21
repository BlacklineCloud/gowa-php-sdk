<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http\Middleware;

use BlacklineCloud\SDK\GowaPHP\Contracts\Http\MiddlewareInterface;
use BlacklineCloud\SDK\GowaPHP\Support\UuidGeneratorInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class IdempotencyMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly UuidGeneratorInterface $uuidGenerator, private readonly string $header = 'Idempotency-Key')
    {
    }

    public function handle(RequestInterface $request, callable $next): ResponseInterface
    {
        if (!$request->hasHeader($this->header)) {
            $request = $request->withHeader($this->header, $this->uuidGenerator->generate());
        }

        return $next($request);
    }
}
