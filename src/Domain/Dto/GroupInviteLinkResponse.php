<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class GroupInviteLinkResponse
{
    public function __construct(
        public string $code,
        public string $message,
        public string $inviteLink,
        public string $groupId,
    ) {
    }
}
