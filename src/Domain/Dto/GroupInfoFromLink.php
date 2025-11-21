<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class GroupInfoFromLink
{
    public function __construct(
        public string $groupId,
        public string $name,
        public string $topic,
        public \DateTimeImmutable $createdAt,
        public int $participantCount,
        public bool $isLocked,
        public bool $isAnnounce,
        public bool $isEphemeral,
        public ?string $description,
    ) {
    }
}
