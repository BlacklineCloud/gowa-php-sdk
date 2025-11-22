<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class ReceiptPayload
{
    /** @param list<string> $ids */
    public function __construct(
        public string $chatId,
        public string $from,
        public array $ids,
        public string $receiptType,
        public string $receiptTypeDescription,
        public string $senderId,
    ) {
    }
}
