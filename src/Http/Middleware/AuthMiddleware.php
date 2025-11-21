<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http\Middleware;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\MiddlewareInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class AuthMiddleware implements MiddlewareInterface
{
    public function __construct(private readonly ClientConfig $config)
    {
    }

    public function handle(RequestInterface $request, callable $next): ResponseInterface
    {
        $auth = base64_encode($this->config->username . ':' . $this->config->password);
        $request = $request
            ->withHeader('Authorization', 'Basic ' . $auth)
            ->withHeader('User-Agent', $this->config->userAgent ?? 'gowa-php-sdk');

        return $next($request);
    }
}
