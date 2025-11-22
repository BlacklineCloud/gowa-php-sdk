<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class WebhookEvent
{
    public function __construct(
        public string $type,
        public WebhookContext $context,
        public ?MessagePayload $message = null,
        public ?ReactionPayload $reaction = null,
        public ?MediaPayload $image = null,
        public ?MediaPayload $video = null,
        public ?MediaPayload $audio = null,
        public ?MediaPayload $document = null,
        public ?MediaPayload $sticker = null,
        public ?ContactPayload $contact = null,
        public ?LocationPayload $location = null,
        public ?ReceiptPayload $receipt = null,
        public ?GroupParticipantsPayload $groupParticipants = null,
        public ?ProtocolPayload $protocol = null,
        public bool $viewOnce = false,
        public bool $forwarded = false,
    ) {
    }
}
