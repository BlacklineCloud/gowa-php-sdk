<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class GenericResponse
{
    public function __construct(
        public string $code,
        public string $message,
        /** @var array<string,mixed>|string|null */
        public array|string|null $results,
    ) {
    }
}
