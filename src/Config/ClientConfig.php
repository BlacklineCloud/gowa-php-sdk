<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Config;

use BlacklineCloud\SDK\GowaPHP\Support\ClockInterface;
use BlacklineCloud\SDK\GowaPHP\Support\UuidGeneratorInterface;

/**
 * Immutable client configuration. Use with() to derive modified copies.
 */
final readonly class ClientConfig
{
    public function __construct(
        public string $baseUri,
        public string $username,
        public string $password,
        public int $requestTimeoutMs = 10000,
        public int $connectTimeoutMs = 5000,
        public int $maxRetries = 2,
        public float $retryBackoffFactor = 2.0,
        public ?string $userAgent = null,
        public ?string $basePath = null,
        public ?ClockInterface $clock = null,
        public ?UuidGeneratorInterface $uuid = null,
    ) {
        $this->guard();
    }

    /**
     * @param array{
     *   baseUri?: string,
     *   username?: string,
     *   password?: string,
     *   requestTimeoutMs?: int,
     *   connectTimeoutMs?: int,
     *   maxRetries?: int,
     *   retryBackoffFactor?: float,
     *   userAgent?: string|null,
     *   basePath?: string|null,
     *   clock?: ClockInterface|null,
     *   uuid?: UuidGeneratorInterface|null
     * } $overrides
     */
    public function with(array $overrides): self
    {
        return new self(
            baseUri: $overrides['baseUri']                       ?? $this->baseUri,
            username: $overrides['username']                     ?? $this->username,
            password: $overrides['password']                     ?? $this->password,
            requestTimeoutMs: $overrides['requestTimeoutMs']     ?? $this->requestTimeoutMs,
            connectTimeoutMs: $overrides['connectTimeoutMs']     ?? $this->connectTimeoutMs,
            maxRetries: $overrides['maxRetries']                 ?? $this->maxRetries,
            retryBackoffFactor: $overrides['retryBackoffFactor'] ?? $this->retryBackoffFactor,
            userAgent: array_key_exists('userAgent', $overrides) ? $overrides['userAgent'] : $this->userAgent,
            basePath: array_key_exists('basePath', $overrides) ? $overrides['basePath'] : $this->basePath,
            clock: $overrides['clock'] ?? $this->clock,
            uuid: $overrides['uuid']   ?? $this->uuid,
        );
    }

    private function guard(): void
    {
        if ($this->baseUri === '') {
            throw new \InvalidArgumentException('baseUri must not be empty');
        }

        if ($this->username === '' || $this->password === '') {
            throw new \InvalidArgumentException('Basic auth credentials must not be empty');
        }

        if ($this->requestTimeoutMs <= 0) {
            throw new \InvalidArgumentException('requestTimeoutMs must be positive');
        }

        if ($this->connectTimeoutMs < 0) {
            throw new \InvalidArgumentException('connectTimeoutMs cannot be negative');
        }

        if ($this->maxRetries < 0) {
            throw new \InvalidArgumentException('maxRetries cannot be negative');
        }

        if ($this->retryBackoffFactor < 1.0) {
            throw new \InvalidArgumentException('retryBackoffFactor must be >= 1.0');
        }
    }
}
