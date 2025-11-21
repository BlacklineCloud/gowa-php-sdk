<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

/** @psalm-immutable */
final readonly class GroupParticipants
{
    /** @param list<GroupParticipant> $participants */
    public function __construct(
        public string $groupId,
        public string $name,
        public array $participants,
    ) {
    }
}
