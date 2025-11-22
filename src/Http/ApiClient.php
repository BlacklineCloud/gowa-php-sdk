<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Http;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;
use BlacklineCloud\SDK\GowaPHP\Serialization\Json;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;

abstract class ApiClient
{
    public function __construct(
        protected readonly ClientConfig $config,
        protected readonly HttpTransportInterface $transport,
        protected readonly RequestFactoryInterface $requestFactory,
        protected readonly StreamFactoryInterface $streamFactory,
    ) {
    }

    /**
     * @param array<string,int|string|bool> $query
     * @param array<string,string> $headers
     * @return array<string,mixed>
     */
    protected function get(string $path, array $query = [], array $headers = []): array
    {
        return $this->send('GET', $path, $query, null, $headers);
    }

    /**
     * @param array<string,mixed>|null $body
     * @param array<string,string> $headers
     * @return array<string,mixed>
     */
    protected function post(string $path, ?array $body = null, array $headers = []): array
    {
        return $this->send('POST', $path, [], $body, $headers);
    }

    /**
     * @param array<string,int|string|bool> $query
     * @param array<string,mixed>|null $body
     * @param array<string,string> $headers
     * @return array<string,mixed>
     */
    private function send(string $method, string $path, array $query, ?array $body, array $headers): array
    {
        $uri     = $this->buildUri($path, $query);
        $request = $this->requestFactory->createRequest($method, $uri)
            ->withHeader('Accept', 'application/json');

        foreach ($headers as $name => $value) {
            $request = $request->withHeader($name, $value);
        }

        if ($body !== null) {
            $payload = Json::encode($body);
            $stream  = $this->streamFactory->createStream($payload);
            $request = $request
                ->withHeader('Content-Type', 'application/json')
                ->withBody($stream);
        }

        $response = $this->transport->sendRequest($request);
        return $this->decodeJson($response);
    }

    /** @return array<string,mixed> */
    private function decodeJson(ResponseInterface $response): array
    {
        $contents = (string) $response->getBody();
        $decoded  = Json::decode($contents);
        if ($decoded !== [] && array_is_list($decoded)) {
            throw new ValidationException('Expected JSON object');
        }
        /** @var array<string,mixed> $decoded */

        return $decoded;
    }

    /** @param array<string,int|string|bool> $query */
    protected function buildUri(string $path, array $query): string
    {
        $base     = rtrim($this->config->baseUri, '/');
        $basePath = $this->config->basePath !== null && $this->config->basePath !== '' ? '/' . trim($this->config->basePath, '/') : '';
        $url      = $base . $basePath . '/' . ltrim($path, '/');
        if ($query !== []) {
            $normalized = [];
            foreach ($query as $key => $value) {
                $normalized[$key] = match (true) {
                    \is_bool($value) => $value ? 'true' : 'false',
                    default          => (string) $value,
                };
            }
            $url .= '?' . http_build_query($normalized);
        }

        return $url;
    }
}
