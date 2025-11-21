<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class PrivacySettings
{
    public function __construct(
        public ?string $groupAdd,
        public ?string $lastSeen,
        public ?string $status,
        public ?string $profile,
        public ?string $readReceipts,
    ) {
    }
}
