<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Value;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final readonly class Timestamp
{
    public function __construct(private \DateTimeImmutable $value)
    {
    }

    public static function fromString(string $value): self
    {
        $dt = \DateTimeImmutable::createFromFormat(DATE_RFC3339, $value);
        if (!$dt) {
            throw new ValidationException('Invalid RFC3339 timestamp: ' . $value);
        }

        return new self($dt);
    }

    public function toRfc3339(): string
    {
        return $this->value->format(DATE_RFC3339);
    }

    public function value(): \DateTimeImmutable
    {
        return $this->value;
    }
}
