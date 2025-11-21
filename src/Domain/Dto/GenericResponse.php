<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class GenericResponse
{
    public function __construct(
        public string $code,
        public string $message,
        public ?string $results,
    ) {
    }
}
