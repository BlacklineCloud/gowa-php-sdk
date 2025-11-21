<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class PinChatResult
{
    public function __construct(
        public string $status,
        public string $message,
        public string $chatJid,
        public bool $pinned,
    ) {
    }
}
