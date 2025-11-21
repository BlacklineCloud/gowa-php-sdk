<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Value;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final readonly class PhoneNumber
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new ValidationException('Phone number must not be empty');
        }

        if (!preg_match('/^[0-9]{6,15}$/', $value)) {
            throw new ValidationException('Invalid phone number');
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
