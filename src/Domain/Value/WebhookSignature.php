<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Value;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final readonly class WebhookSignature
{
    private const PREFIX = 'sha256=';

    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new ValidationException('Signature must not be empty');
        }

        if (!str_starts_with($value, self::PREFIX)) {
            throw new ValidationException('Signature must start with ' . self::PREFIX);
        }

        $hex = substr($value, strlen(self::PREFIX));
        if (!preg_match('/^[a-f0-9]{64}$/i', $hex)) {
            throw new ValidationException('Invalid SHA256 signature');
        }
    }

    public function raw(): string
    {
        return $this->value;
    }

    public function hex(): string
    {
        return substr($this->value, strlen(self::PREFIX));
    }
}
