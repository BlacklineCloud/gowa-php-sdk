<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class MessagePayload
{
    public function __construct(
        public string $text,
        public string $id,
        public ?string $repliedId,
        public ?string $quotedMessage,
    ) {
    }
}
