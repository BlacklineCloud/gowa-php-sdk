<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Client;

use BlacklineCloud\SDK\GowaPHP\Client\SendClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SendResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class SendClientTest extends TestCase
{
    public function testSendText(): void
    {
        $psr17 = new Psr17Factory();
        $body = json_encode([
            'code' => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                'message_id' => 'abc',
                'status' => 'sent',
            ],
        ], JSON_THROW_ON_ERROR);
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], $body));
        $client = $this->client($transport, $psr17);

        $dto = $client->text('jid@s.whatsapp.net', 'hello');

        self::assertSame('abc', $dto->messageId);
        self::assertSame('https://api.example.test/send/message', (string) $transport->lastRequest?->getUri());
    }

    public function testSendPoll(): void
    {
        $psr17 = new Psr17Factory();
        $body = json_encode([
            'code' => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                'message_id' => 'poll',
                'status' => 'sent',
            ],
        ], JSON_THROW_ON_ERROR);
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], $body));
        $client = $this->client($transport, $psr17);

        $client->poll('jid@s.whatsapp.net', 'Choose', 'one', 'two');

        $payload = json_decode((string) $transport->lastRequest?->getBody(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame(['one', 'two'], $payload['options']);
    }

    public function testChatPresence(): void
    {
        $psr17 = new Psr17Factory();
        $body = json_encode([
            'code' => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                'message_id' => 'presence',
                'status' => 'sent',
            ],
        ], JSON_THROW_ON_ERROR);
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], $body));
        $client = $this->client($transport, $psr17);

        $client->chatPresence('jid@s.whatsapp.net', \BlacklineCloud\SDK\GowaPHP\Domain\Enum\PresenceState::Composing);

        $payload = json_decode((string) $transport->lastRequest?->getBody(), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame('composing', $payload['presence']);
    }

    private function client(FakeTransport $transport, Psr17Factory $psr17): SendClient
    {
        return new SendClient(
            new ClientConfig('https://api.example.test', 'u', 'p'),
            $transport,
            $psr17,
            $psr17,
            new SendResponseHydrator(),
        );
    }
}
