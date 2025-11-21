<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class ChatMessage
{
    public function __construct(
        public string $id,
        public string $chatJid,
        public string $senderJid,
        public string $content,
        public \DateTimeImmutable $timestamp,
        public bool $isFromMe,
        public ?string $mediaType,
        public ?string $filename,
        public ?string $url,
        public ?int $fileLength,
        public ?\DateTimeImmutable $createdAt,
        public ?\DateTimeImmutable $updatedAt,
    ) {
    }
}
