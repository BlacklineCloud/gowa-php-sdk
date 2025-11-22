<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\Chat;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ChatList;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ChatListResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\Pagination;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class ChatListResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): ChatListResponse
    {
        $reader = new ArrayReader($payload);
        $resultsReader = new ArrayReader($reader->requireObject('results'), '$.results');
        $pagination = $this->pagination($resultsReader->requireObject('pagination'));
        $chats = [];
        foreach ($resultsReader->requireObject('data') as $row) {
            $rowReader = new ArrayReader((array) $row, '$.results.data');
            $chats[] = new Chat(
                jid: $rowReader->requireString('jid'),
                name: $rowReader->requireString('name'),
                lastMessageTime: new \DateTimeImmutable($rowReader->requireString('last_message_time')),
                ephemeralExpiration: $rowReader->requireInt('ephemeral_expiration'),
                createdAt: $this->optionalDate($rowReader->optionalString('created_at')),
                updatedAt: $this->optionalDate($rowReader->optionalString('updated_at')),
            );
        }

        return new ChatListResponse(
            code: $reader->requireString('code'),
            message: $reader->requireString('message'),
            results: new ChatList($chats, $pagination),
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
}
