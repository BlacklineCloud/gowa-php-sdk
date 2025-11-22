<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Client;

use BlacklineCloud\SDK\GowaPHP\Client\ChatClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ChatListResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ChatMessagesResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LabelChatResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\PinChatResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class ChatClientTest extends TestCase
{
    public function testListChats(): void
    {
        $psr17 = new Psr17Factory();
        $body  = json_encode([
            'code'    => 'SUCCESS',
            'message' => 'Success get chat list',
            'results' => [
                'data' => [
                    [
                        'jid'                  => 'jid@s.whatsapp.net',
                        'name'                 => 'Chat',
                        'last_message_time'    => '2024-01-15T10:30:00Z',
                        'ephemeral_expiration' => 0,
                        'created_at'           => '2024-01-10T08:00:00Z',
                        'updated_at'           => '2024-01-15T10:30:00Z',
                    ],
                ],
                'pagination' => [
                    'limit'  => 25,
                    'offset' => 0,
                    'total'  => 1,
                ],
            ],
        ], JSON_THROW_ON_ERROR);
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], $body));
        $client    = new ChatClient(
            new ClientConfig('https://api.example.test', 'u', 'p'),
            $transport,
            $psr17,
            $psr17,
            new ChatListResponseHydrator(),
            new ChatMessagesResponseHydrator(),
            new LabelChatResponseHydrator(),
            new PinChatResponseHydrator(),
        );

        $dto = $client->list();

        self::assertSame('Chat', $dto->results->data[0]->name);
        self::assertSame('https://api.example.test/chats?limit=25&offset=0&has_media=false', (string) $transport->lastRequest?->getUri());
    }
}
