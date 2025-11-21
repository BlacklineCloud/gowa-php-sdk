<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class GroupParticipant
{
    public function __construct(
        public string $jid,
        public string $phoneNumber,
        public ?string $lid,
        public ?string $displayName,
        public bool $isAdmin,
        public bool $isSuperAdmin,
    ) {
    }
}
