<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Client;

use BlacklineCloud\SDK\GowaPHP\Client\MessageClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\MessageActionResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class MessageClientTest extends TestCase
{
    public function testRevoke(): void
    {
        $psr17 = new Psr17Factory();
        $body  = json_encode([
            'code'    => 'SUCCESS',
            'message' => 'ok',
            'results' => [
                'status'     => 'success',
                'message'    => 'revoked',
                'message_id' => 'mid',
            ],
        ], JSON_THROW_ON_ERROR);
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], $body));
        $client    = new MessageClient(
            new ClientConfig('https://api.example.test', 'u', 'p'),
            $transport,
            $psr17,
            $psr17,
            new MessageActionResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $dto = $client->revoke('mid');

        self::assertSame('mid', $dto->results->messageId);
        self::assertStringContainsString('/message/mid/revoke', (string) $transport->lastRequest?->getUri());
    }

    public function testMessageActions(): void
    {
        $psr17 = new Psr17Factory();
        $body  = json_encode([
            'code'    => 'SUCCESS',
            'message' => 'ok',
            'results' => [
                'status'     => 'success',
                'message'    => 'done',
                'message_id' => 'mid',
            ],
        ], JSON_THROW_ON_ERROR);
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], $body));
        $client    = new MessageClient(
            new ClientConfig('https://api.example.test', 'u', 'p'),
            $transport,
            $psr17,
            $psr17,
            new MessageActionResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $client->delete('mid');
        $client->reaction('mid', '👍');
        $client->update('mid', 'text');
        $client->read('mid');
        $client->star('mid');
        $client->unstar('mid');

        self::assertNotNull($transport->lastRequest);
    }
}
