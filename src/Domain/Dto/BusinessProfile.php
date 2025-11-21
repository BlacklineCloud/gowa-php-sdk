<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class BusinessProfile
{
    public function __construct(
        public ?string $description,
        public ?string $email,
        public ?string $website,
        public ?string $businessHours,
    ) {
    }
}
