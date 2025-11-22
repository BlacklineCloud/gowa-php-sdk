<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\MiddlewareInterface;
use BlacklineCloud\SDK\GowaPHP\Exception\AuthenticationException;
use BlacklineCloud\SDK\GowaPHP\Exception\RateLimitException;
use BlacklineCloud\SDK\GowaPHP\Exception\ServerException;
use BlacklineCloud\SDK\GowaPHP\Exception\TransportException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface as Psr18Client;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

class Psr18Transport implements HttpTransportInterface
{
    /** @var MiddlewareInterface[] */
    private array $middleware;

    public function __construct(
        private readonly Psr18Client $client,
        private readonly ClientConfig $config,
        private readonly LoggerInterface $logger,
        MiddlewareInterface ...$middleware,
    ) {
        $this->middleware = $middleware;
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $pipeline = array_reduce(
            array_reverse($this->middleware),
            fn (callable $next, MiddlewareInterface $middleware) => fn (RequestInterface $req) => $middleware->handle($req, $next),
            fn (RequestInterface $req) => $this->doSend($req)
        );

        return $pipeline($request);
    }

    private function doSend(RequestInterface $request): ResponseInterface
    {
        try {
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->logger->error('Transport error', [
                'exception' => $e,
                'base_uri' => $this->config->baseUri,
            ]);
            throw new TransportException($e->getMessage(), previous: $e);
        }

        $status = $response->getStatusCode();

        if ($status === 401 || $status === 403) {
            throw new AuthenticationException('Authentication failed');
        }

        if ($status === 429) {
            $retryAfter = $this->retryAfter($response);
            throw new RateLimitException(retryAfterSeconds: $retryAfter);
        }

        if ($status >= 500) {
            throw new ServerException('Server error', statusCode: $status);
        }

        return $response;
    }

    private function retryAfter(ResponseInterface $response): ?int
    {
        $header = $response->getHeaderLine('Retry-After');
        if ($header === '') {
            return null;
        }

        if (is_numeric($header)) {
            return (int) $header;
        }

        $timestamp = strtotime($header);
        if ($timestamp === false) {
            return null;
        }

        return max(0, $timestamp - time());
    }
}
