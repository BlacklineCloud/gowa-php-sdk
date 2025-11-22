<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\Chat;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ChatMessage;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ChatMessages;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ChatMessagesResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\Pagination;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class ChatMessagesResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): ChatMessagesResponse
    {
        $reader = new ArrayReader($payload);
        $res = new ArrayReader($reader->requireObject('results'), '$.results');
        $pagination = $this->pagination($res->requireObject('pagination'));
        $chatInfo = $this->chat(new ArrayReader($res->requireObject('chat_info'), '$.results.chat_info'));

        $messages = [];
        foreach ($res->requireObject('data') as $row) {
            $r = new ArrayReader((array) $row, '$.results.data');
            $messages[] = new ChatMessage(
                id: $r->requireString('id'),
                chatJid: $r->requireString('chat_jid'),
                senderJid: $r->requireString('sender_jid'),
                content: $r->requireString('content'),
                timestamp: new \DateTimeImmutable($r->requireString('timestamp')),
                isFromMe: $r->requireBool('is_from_me'),
                mediaType: $r->optionalString('media_type'),
                filename: $r->optionalString('filename'),
                url: $r->optionalString('url'),
                fileLength: $r->optionalInt('file_length'),
                createdAt: $this->optionalDate($r->optionalString('created_at')),
                updatedAt: $this->optionalDate($r->optionalString('updated_at')),
            );
        }

        return new ChatMessagesResponse(
            code: $reader->requireString('code'),
            message: $reader->requireString('message'),
            results: new ChatMessages($messages, $pagination, $chatInfo),
        );
    }

    /** @param array<int|string,mixed> $data */
    private function pagination(array $data): Pagination
    {
        $r = new ArrayReader($data, '$.pagination');
        return new Pagination($r->requireInt('limit'), $r->requireInt('offset'), $r->requireInt('total'));
    }

    private function optionalDate(?string $value): ?\DateTimeImmutable
    {
        return $value === null ? null : new \DateTimeImmutable($value);
    }

    private function chat(ArrayReader $r): Chat
    {
        return new Chat(
            jid: $r->requireString('jid'),
            name: $r->requireString('name'),
            lastMessageTime: new \DateTimeImmutable($r->requireString('last_message_time')),
            ephemeralExpiration: $r->requireInt('ephemeral_expiration'),
            createdAt: $this->optionalDate($r->optionalString('created_at')),
            updatedAt: $this->optionalDate($r->optionalString('updated_at')),
        );
    }
}
