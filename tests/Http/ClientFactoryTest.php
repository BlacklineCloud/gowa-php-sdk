<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Http;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Http\ClientFactory;
use BlacklineCloud\SDK\GowaPHP\Support\NativeUuidGenerator;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\NullLogger;

final class ClientFactoryTest extends TestCase
{
    public function testCreatesSendClient(): void
    {
        $psr17     = new Psr17Factory();
        $mockPsr18 = new class () implements ClientInterface {
            public ?RequestInterface $lastRequest = null;

            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                $this->lastRequest = $request;
                return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                    'code'    => 'SUCCESS',
                    'message' => 'ok',
                    'results' => [
                        'message_id' => 'abc',
                        'status'     => 'sent',
                    ],
                ], JSON_THROW_ON_ERROR));
            }
        };

        $factory = new ClientFactory(
            requestFactory: $psr17,
            streamFactory: $psr17,
            psr18: $mockPsr18,
            logger: new NullLogger(),
            uuid: new NativeUuidGenerator(),
        );

        $send = $factory->createSendClient(new ClientConfig('https://api.example.test', 'u', 'p'));
        $dto  = $send->text('628111111111@s.whatsapp.net', 'hi');

        self::assertSame('abc', $dto->messageId);
        self::assertSame('https://api.example.test/send/message', (string) $mockPsr18->lastRequest?->getUri());
    }

    public function testCreatesAllClients(): void
    {
        $psr17 = new Psr17Factory();
        $psr18 = new class () implements ClientInterface {
            public function sendRequest(RequestInterface $request): ResponseInterface
            {
                return new Response(200, ['Content-Type' => 'application/json'], json_encode([
                    'code'    => 'SUCCESS',
                    'message' => 'ok',
                    'results' => ['status' => 'ok'],
                ], JSON_THROW_ON_ERROR));
            }
        };

        $factory = new ClientFactory(
            requestFactory: $psr17,
            streamFactory: $psr17,
            psr18: $psr18,
            logger: new NullLogger(),
            uuid: new NativeUuidGenerator(),
        );

        $config = new ClientConfig('https://api.example.test', 'u', 'p');
        self::assertInstanceOf(\BlacklineCloud\SDK\GowaPHP\Client\AppClient::class, $factory->createAppClient($config));
        self::assertInstanceOf(\BlacklineCloud\SDK\GowaPHP\Client\UserClient::class, $factory->createUserClient($config));
        self::assertInstanceOf(\BlacklineCloud\SDK\GowaPHP\Client\MessageClient::class, $factory->createMessageClient($config));
        self::assertInstanceOf(\BlacklineCloud\SDK\GowaPHP\Client\ChatClient::class, $factory->createChatClient($config));
        self::assertInstanceOf(\BlacklineCloud\SDK\GowaPHP\Client\GroupClient::class, $factory->createGroupClient($config));
        self::assertInstanceOf(\BlacklineCloud\SDK\GowaPHP\Client\NewsletterClient::class, $factory->createNewsletterClient($config));
    }
}
