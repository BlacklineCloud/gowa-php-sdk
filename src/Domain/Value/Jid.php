<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Value;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final readonly class Jid
{
    private const PATTERN = '/^[0-9]+@(s\.whatsapp\.net|g\.us|lid)$/';

    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new ValidationException('JID must not be empty');
        }

        if (!preg_match(self::PATTERN, $value)) {
            throw new ValidationException('Invalid JID format: ' . $value);
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function value(): string
    {
        return $this->value;
    }
}
