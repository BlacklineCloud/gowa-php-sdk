<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Value;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final readonly class MediaPath
{
    public function __construct(private string $value)
    {
        if ($value === '') {
            throw new ValidationException('Media path must not be empty');
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
