<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Config;

use BlacklineCloud\SDK\GowaPHP\Support\SystemClock;
use BlacklineCloud\SDK\GowaPHP\Support\NativeUuidGenerator;

final class ClientConfigBuilder
{
    /** @param array<string,string|null> $input */
    public static function fromArray(array $input): ClientConfig
    {
        $baseUri = self::requireStringKey($input, 'base_uri');
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
            basePath: $input['base_path'] ?? null,
            clock: new SystemClock(),
            uuid: new NativeUuidGenerator(),
        );
    }

    public static function fromEnv(): ClientConfig
    {
        return self::fromArray([
            'base_uri' => getenv('GOWA_BASE_URI') ?: '',
            'username' => getenv('GOWA_USERNAME') ?: '',
            'password' => getenv('GOWA_PASSWORD') ?: '',
            'request_timeout_ms' => getenv('GOWA_REQUEST_TIMEOUT_MS') ?: '10000',
            'connect_timeout_ms' => getenv('GOWA_CONNECT_TIMEOUT_MS') ?: '5000',
            'max_retries' => getenv('GOWA_MAX_RETRIES') ?: '2',
            'retry_backoff_factor' => getenv('GOWA_RETRY_BACKOFF_FACTOR') ?: '2.0',
            'user_agent' => getenv('GOWA_USER_AGENT') ?: 'gowa-php-sdk',
            'base_path' => getenv('GOWA_BASE_PATH') ?: null,
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
