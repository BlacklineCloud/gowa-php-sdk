<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class ProtocolPayload
{
    public function __construct(
        public string $action,
        public ?string $revokedMessageId,
        public ?string $originalMessageId,
        public ?string $editedText,
    ) {
    }
}
