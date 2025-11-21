<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final class ArrayReader
{
    /** @param array<string,mixed> $data */
    public function __construct(private array $data, private string $path = '$')
    {
    }

    public function requireString(string $key): string
    {
        $value = $this->requireKey($key);
        if (!\is_string($value)) {
            throw new ValidationException($this->err($key, 'string'));
        }

        return $value;
    }

    public function requireInt(string $key): int
    {
        $value = $this->requireKey($key);
        if (!\is_int($value)) {
            throw new ValidationException($this->err($key, 'int'));
        }

        return $value;
    }

    public function requireBool(string $key): bool
    {
        $value = $this->requireKey($key);
        if (!\is_bool($value)) {
            throw new ValidationException($this->err($key, 'bool'));
        }

        return $value;
    }

    public function optionalString(string $key): ?string
    {
        if (!array_key_exists($key, $this->data) || $this->data[$key] === null) {
            return null;
        }
        $value = $this->data[$key];
        if (!\is_string($value)) {
            throw new ValidationException($this->err($key, 'string|null'));
        }

        return $value;
    }

    /** @return array<string,mixed>|null */
    public function optionalObject(string $key): ?array
    {
        if (!array_key_exists($key, $this->data) || $this->data[$key] === null) {
            return null;
        }

        $value = $this->data[$key];
        if (!\is_array($value)) {
            throw new ValidationException($this->err($key, 'object|null'));
        }

        return $value;
    }

    public function optionalBool(string $key): ?bool
    {
        if (!array_key_exists($key, $this->data) || $this->data[$key] === null) {
            return null;
        }

        $value = $this->data[$key];
        if (!\is_bool($value)) {
            throw new ValidationException($this->err($key, 'bool|null'));
        }

        return $value;
    }

    /** @return array<string,mixed> */
    public function requireObject(string $key): array
    {
        $value = $this->requireKey($key);
        if (!\is_array($value)) {
            throw new ValidationException($this->err($key, 'object'));
        }

        return $value;
    }

    private function requireKey(string $key): mixed
    {
        if (!array_key_exists($key, $this->data)) {
            throw new ValidationException("Missing required field {$this->path}.{$key}");
        }

        return $this->data[$key];
    }

    private function err(string $key, string $expected): string
    {
        $actual = get_debug_type($this->data[$key] ?? null);

        return sprintf('Expected %s.%s to be %s, got %s', $this->path, $key, $expected, $actual);
    }
}
