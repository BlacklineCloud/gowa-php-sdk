<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class Chat
{
    public function __construct(
        public string $jid,
        public string $name,
        public \DateTimeImmutable $lastMessageTime,
        public int $ephemeralExpiration,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {
    }
}
