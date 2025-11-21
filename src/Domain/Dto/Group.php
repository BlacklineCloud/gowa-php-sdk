<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

/** @psalm-immutable */
final readonly class Group
{
    /** @param list<GroupParticipant> $participants */
    public function __construct(
        public string $jid,
        public string $ownerJid,
        public string $name,
        public ?\DateTimeImmutable $nameSetAt,
        public ?string $nameSetBy,
        public ?string $topic,
        public ?string $topicId,
        public ?\DateTimeImmutable $topicSetAt,
        public ?string $topicSetBy,
        public bool $topicDeleted,
        public bool $isLocked,
        public bool $isAnnounce,
        public ?string $announceVersionId,
        public bool $isEphemeral,
        public int $disappearingTimer,
        public bool $isIncognito,
        public bool $isParent,
        public ?string $defaultMembershipApprovalMode,
        public ?string $linkedParentJid,
        public bool $isDefaultSubGroup,
        public bool $isJoinApprovalRequired,
        public ?\DateTimeImmutable $groupCreated,
        public ?string $participantVersionId,
        public array $participants,
        public ?string $memberAddMode,
    ) {
    }
}
