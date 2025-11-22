<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\ContactPayload;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\GroupParticipantsPayload;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\LocationPayload;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\MediaPayload;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\MessagePayload;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\ProtocolPayload;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\ReactionPayload;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\ReceiptPayload;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\WebhookContext;
use BlacklineCloud\SDK\GowaPHP\Webhook\Model\WebhookEvent;

final class WebhookEventHydrator
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): WebhookEvent
    {
        $reader = new ArrayReader($payload);
        $context = new WebhookContext(
            senderId: $reader->requireString('sender_id'),
            chatId: $reader->requireString('chat_id'),
            from: $reader->requireString('from'),
            timestamp: new \DateTimeImmutable($reader->requireString('timestamp')),
            pushname: $reader->optionalString('pushname'),
        );

        // Receipts
        if (($payload['event'] ?? null) === 'message.ack') {
            $res = new ArrayReader($reader->requireObject('payload'), '$.payload');
            $receipt = new ReceiptPayload(
                chatId: $res->requireString('chat_id'),
                from: $res->requireString('from'),
                ids: $res->requireObject('ids'),
                receiptType: $res->requireString('receipt_type'),
                receiptTypeDescription: $res->requireString('receipt_type_description'),
                senderId: $res->requireString('sender_id'),
            );

            return new WebhookEvent('receipt', $context, receipt: $receipt);
        }

        // Group participant events
        if (($payload['event'] ?? null) === 'group.participants') {
            $res = new ArrayReader($reader->requireObject('payload'), '$.payload');
            $group = new GroupParticipantsPayload(
                chatId: $res->requireString('chat_id'),
                type: $res->requireString('type'),
                jids: $res->requireObject('jids'),
            );

            return new WebhookEvent('group.participants', $context, groupParticipants: $group);
        }

        // Protocol actions
        if (($payload['action'] ?? null) !== null) {
            return new WebhookEvent(
                type: (string) $payload['action'],
                context: $context,
                protocol: new ProtocolPayload(
                    action: (string) $payload['action'],
                    revokedMessageId: $payload['revoked_message_id'] ?? null,
                    originalMessageId: $payload['original_message_id'] ?? null,
                    editedText: $payload['edited_text'] ?? null,
                ),
            );
        }

        // Message-type events
        $message = null;
        if (isset($payload['message'])) {
            $msg = new ArrayReader($reader->requireObject('message'), '$.message');
            $message = new MessagePayload(
                text: $msg->requireString('text'),
                id: $msg->requireString('id'),
                repliedId: $msg->optionalString('replied_id'),
                quotedMessage: $msg->optionalString('quoted_message'),
            );
        }

        $reaction = null;
        if (isset($payload['reaction'])) {
            $re = new ArrayReader($reader->requireObject('reaction'), '$.reaction');
            $reaction = new ReactionPayload(
                message: $re->requireString('message'),
                id: $re->requireString('id'),
            );
        }

        $image = $this->maybeMedia($payload, 'image');
        $video = $this->maybeMedia($payload, 'video');
        $audio = $this->maybeMedia($payload, 'audio');
        $document = $this->maybeMedia($payload, 'document');
        $sticker = $this->maybeMedia($payload, 'sticker');

        $contact = null;
        if (isset($payload['contact'])) {
            $c = new ArrayReader($reader->requireObject('contact'), '$.contact');
            $contact = new ContactPayload(
                displayName: $c->requireString('displayName'),
                vcard: $c->requireString('vcard'),
            );
        }

        $location = null;
        if (isset($payload['location'])) {
            $loc = new ArrayReader($reader->requireObject('location'), '$.location');
            $location = new LocationPayload(
                latitude: (float) $loc->requireString('degreesLatitude'),
                longitude: (float) $loc->requireString('degreesLongitude'),
                name: $loc->optionalString('name'),
                address: $loc->optionalString('address'),
            );
        }

        $type = 'message';
        if ($image || $video || $audio || $document || $sticker) {
            $type = 'media';
        }
        if ($reaction) {
            $type = 'reaction';
        }

        return new WebhookEvent(
            type: $type,
            context: $context,
            message: $message,
            reaction: $reaction,
            image: $image,
            video: $video,
            audio: $audio,
            document: $document,
            sticker: $sticker,
            contact: $contact,
            location: $location,
            viewOnce: (bool) ($payload['view_once'] ?? false),
            forwarded: (bool) ($payload['forwarded'] ?? false),
        );
    }

    private function maybeMedia(array $payload, string $key): ?MediaPayload
    {
        if (!isset($payload[$key])) {
            return null;
        }

        $media = new ArrayReader((array) $payload[$key], '$.' . $key);
        return new MediaPayload(
            mediaPath: $media->requireString('media_path'),
            mimeType: $media->requireString('mime_type'),
            caption: $media->optionalString('caption'),
        );
    }
}
