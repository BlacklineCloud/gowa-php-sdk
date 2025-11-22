<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Config;

use BlacklineCloud\SDK\GowaPHP\Support\NativeUuidGenerator;
use BlacklineCloud\SDK\GowaPHP\Support\SystemClock;

final class ClientConfigBuilder
{
    /** @param array<string,string|null> $input */
    public static function fromArray(array $input): ClientConfig
    {
        $baseUri  = self::requireStringKey($input, 'base_uri');
        $username = self::requireStringKey($input, 'username');
        $password = self::requireStringKey($input, 'password');

        return new ClientConfig(
            baseUri: $baseUri,
            username: $username,
            password: $password,
            requestTimeoutMs: isset($input['request_timeout_ms']) ? (int) $input['request_timeout_ms'] : 10000,
            connectTimeoutMs: isset($input['connect_timeout_ms']) ? (int) $input['connect_timeout_ms'] : 5000,
            maxRetries: isset($input['max_retries']) ? (int) $input['max_retries'] : 2,
            retryBackoffFactor: isset($input['retry_backoff_factor']) ? (float) $input['retry_backoff_factor'] : 2.0,
            userAgent: $input['user_agent'] ?? 'gowa-php-sdk',
            basePath: $input['base_path']   ?? null,
            clock: new SystemClock(),
            uuid: new NativeUuidGenerator(),
        );
    }

    public static function fromEnv(): ClientConfig
    {
        $baseUri = getenv('GOWA_BASE_URI');
        $username = getenv('GOWA_USERNAME');
        $password = getenv('GOWA_PASSWORD');
        $requestTimeoutMs = getenv('GOWA_REQUEST_TIMEOUT_MS');
        $connectTimeoutMs = getenv('GOWA_CONNECT_TIMEOUT_MS');
        $maxRetries = getenv('GOWA_MAX_RETRIES');
        $retryBackoff = getenv('GOWA_RETRY_BACKOFF_FACTOR');
        $userAgent = getenv('GOWA_USER_AGENT');
        $basePath = getenv('GOWA_BASE_PATH');

        return self::fromArray([
            'base_uri' => $baseUri === false ? '' : $baseUri,
            'username' => $username === false ? '' : $username,
            'password' => $password === false ? '' : $password,
            'request_timeout_ms' => $requestTimeoutMs === false ? '10000' : $requestTimeoutMs,
            'connect_timeout_ms' => $connectTimeoutMs === false ? '5000' : $connectTimeoutMs,
            'max_retries' => $maxRetries === false ? '2' : $maxRetries,
            'retry_backoff_factor' => $retryBackoff === false ? '2.0' : $retryBackoff,
            'user_agent' => $userAgent === false ? 'gowa-php-sdk' : $userAgent,
            'base_path' => $basePath === false ? null : $basePath,
        ]);
    }

    /**
     * @param array<string,string|null> $input
     */
    private static function requireStringKey(array $input, string $key): string
    {
        if (!array_key_exists($key, $input)) {
            throw new \InvalidArgumentException("Missing required config key {$key}");
        }

        $value = $input[$key];
        if ($value === '') {
            throw new \InvalidArgumentException("Config key {$key} must not be empty");
        }

        return (string) $value;
    }
}
