<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ChatListResponseHydrator;
use PHPUnit\Framework\TestCase;

final class ChatListResponseHydratorTest extends TestCase
{
    public function testHydratesChatList(): void
    {
        $hydrator = new ChatListResponseHydrator();
        $dto      = $hydrator->hydrate([
            'code'    => 'SUCCESS',
            'message' => 'Success get chat list',
            'results' => [
                'data' => [
                    [
                        'jid'                  => '6289685028129@s.whatsapp.net',
                        'name'                 => 'John Doe',
                        'last_message_time'    => '2024-01-15T10:30:00Z',
                        'ephemeral_expiration' => 0,
                        'created_at'           => '2024-01-10T08:00:00Z',
                        'updated_at'           => '2024-01-15T10:30:00Z',
                    ],
                ],
                'pagination' => [
                    'limit'  => 25,
                    'offset' => 0,
                    'total'  => 150,
                ],
            ],
        ]);

        self::assertSame('SUCCESS', $dto->code);
        self::assertSame('John Doe', $dto->results->data[0]->name);
        self::assertSame(150, $dto->results->pagination->total);
    }
}
