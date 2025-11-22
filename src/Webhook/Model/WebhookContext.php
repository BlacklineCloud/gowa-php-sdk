<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class WebhookContext
{
    public function __construct(
        public string $senderId,
        public string $chatId,
        public string $from,
        public \DateTimeImmutable $timestamp,
        public ?string $pushname,
    ) {
    }
}
