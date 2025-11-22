<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class GroupParticipantsPayload
{
    /** @param list<string> $jids */
    public function __construct(
        public string $chatId,
        public string $type,
        public array $jids,
    ) {
    }
}
