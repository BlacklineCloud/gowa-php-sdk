<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Http;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Exception\AuthenticationException;
use BlacklineCloud\SDK\GowaPHP\Exception\RateLimitException;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\AuthMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\CorrelationIdMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\IdempotencyMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\RetryMiddleware;
use BlacklineCloud\SDK\GowaPHP\Http\Psr18Transport;
use BlacklineCloud\SDK\GowaPHP\Support\NativeUuidGenerator;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\NullLogger;

final class Psr18TransportTest extends TestCase
{
    public function testRetryOn429ThrowsRateLimit(): void
    {
        $client = $this->mockClient(429);
        $config = new ClientConfig('https://api.example.test', 'u', 'p');
        $transport = new Psr18Transport(
            $client,
            $config,
            new NullLogger(),
            new AuthMiddleware($config),
            new IdempotencyMiddleware(new NativeUuidGenerator()),
            new CorrelationIdMiddleware(new NativeUuidGenerator()),
            new RetryMiddleware($config),
        );

        $this->expectException(RateLimitException::class);
        $transport->sendRequest($this->dummyRequest());
    }

    public function test401ThrowsAuthException(): void
    {
        $client = $this->mockClient(401);
        $config = new ClientConfig('https://api.example.test', 'u', 'p');
        $transport = new Psr18Transport($client, $config, new NullLogger());

        $this->expectException(AuthenticationException::class);
        $transport->sendRequest($this->dummyRequest());
    }

    private function mockClient(int $status): ClientInterface
    {
        return new class($status) implements ClientInterface {
            public function __construct(private int $status)
            {
            }

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                return new class($this->status) implements ResponseInterface {
                    public function __construct(private int $status)
                    {
                    }

                    public function getStatusCode(): int { return $this->status; }
                    public function getReasonPhrase(): string { return ''; }
                    public function getProtocolVersion(): string { return '1.1'; }
                    public function withProtocolVersion($version): self { return $this; }
                    public function getHeaders(): array { return []; }
                    public function hasHeader($name): bool { return false; }
                    public function getHeader($name): array { return []; }
                    public function getHeaderLine($name): string { return ''; }
                    public function withHeader($name, $value): self { return $this; }
                    public function withAddedHeader($name, $value): self { return $this; }
                    public function withoutHeader($name): self { return $this; }
                    public function getBody() { return new class {
                        public function __toString(): string { return ''; }
                        public function close(): void {}
                        public function detach() { return null; }
                        public function getSize() { return 0; }
                        public function tell() { return 0; }
                        public function eof() { return true; }
                        public function isSeekable() { return false; }
                        public function seek($offset, $whence = SEEK_SET): void {}
                        public function rewind(): void {}
                        public function isWritable() { return false; }
                        public function write($string) { return 0; }
                        public function isReadable() { return false; }
                        public function read($length) { return ''; }
                        public function getContents() { return ''; }
                        public function getMetadata($key = null) { return null; }
                    }; }
                    public function withBody(\Psr\Http\Message\StreamInterface $body): self { return $this; }
                };
            }
        };
    }

    private function dummyRequest(): RequestInterface
    {
        return new class implements RequestInterface {
            public function getRequestTarget() { return '/'; }
            public function withRequestTarget($requestTarget): self { return $this; }
            public function getMethod() { return 'GET'; }
            public function withMethod($method): self { return $this; }
            public function getUri() { return null; }
            public function withUri(\Psr\Http\Message\UriInterface $uri, $preserveHost = false): self { return $this; }
            public function getProtocolVersion() { return '1.1'; }
            public function withProtocolVersion($version): self { return $this; }
            public function getHeaders() { return []; }
            public function hasHeader($name) { return false; }
            public function getHeader($name) { return []; }
            public function getHeaderLine($name) { return ''; }
            public function withHeader($name, $value): self { return $this; }
            public function withAddedHeader($name, $value): self { return $this; }
            public function withoutHeader($name): self { return $this; }
            public function getBody() { return new class {
                public function __toString(): string { return ''; }
                public function close(): void {}
                public function detach() { return null; }
                public function getSize() { return 0; }
                public function tell() { return 0; }
                public function eof() { return true; }
                public function isSeekable() { return false; }
                public function seek($offset, $whence = SEEK_SET): void {}
                public function rewind(): void {}
                public function isWritable() { return false; }
                public function write($string) { return 0; }
                public function isReadable() { return false; }
                public function read($length) { return ''; }
                public function getContents() { return ''; }
                public function getMetadata($key = null) { return null; }
            }; }
            public function withBody(\Psr\Http\Message\StreamInterface $body): self { return $this; }
        };
    }
}
