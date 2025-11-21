<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ChatMessagesResponseHydrator;
use PHPUnit\Framework\TestCase;

final class ChatMessagesResponseHydratorTest extends TestCase
{
    public function testHydratesChatMessages(): void
    {
        $hydrator = new ChatMessagesResponseHydrator();
        $dto = $hydrator->hydrate([
            'code' => 'SUCCESS',
            'message' => 'Success get chat messages',
            'results' => [
                'data' => [
                    [
                        'id' => 'abc',
                        'chat_jid' => 'chat@s.whatsapp.net',
                        'sender_jid' => 'user@s.whatsapp.net',
                        'content' => 'hello',
                        'timestamp' => '2024-01-15T10:30:00Z',
                        'is_from_me' => false,
                        'media_type' => null,
                        'filename' => null,
                        'url' => null,
                        'file_length' => null,
                        'created_at' => '2024-01-15T10:30:00Z',
                        'updated_at' => '2024-01-15T10:30:00Z',
                    ],
                ],
                'pagination' => [
                    'limit' => 50,
                    'offset' => 0,
                    'total' => 1250,
                ],
                'chat_info' => [
                    'jid' => 'chat@s.whatsapp.net',
                    'name' => 'Chat',
                    'last_message_time' => '2024-01-15T10:30:00Z',
                    'ephemeral_expiration' => 0,
                    'created_at' => '2024-01-10T08:00:00Z',
                    'updated_at' => '2024-01-15T10:30:00Z'
                ],
            ],
        ]);

        self::assertSame('abc', $dto->results->data[0]->id);
        self::assertSame('chat@s.whatsapp.net', $dto->results->chatInfo->jid);
        self::assertSame(1250, $dto->results->pagination->total);
    }
}
