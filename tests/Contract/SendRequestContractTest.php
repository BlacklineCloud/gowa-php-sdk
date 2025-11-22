<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Contract;

use BlacklineCloud\SDK\GowaPHP\Client\SendClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SendResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class SendRequestContractTest extends TestCase
{
    public function testSendTextMatchesContract(): void
    {
        $psr17 = new Psr17Factory();
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], '{}'));
        $client = new SendClient(
            new ClientConfig('https://api.example.test', 'u', 'p'),
            $transport,
            $psr17,
            $psr17,
            new SendResponseHydrator(),
        );

        $client->text('628123456789@s.whatsapp.net', 'Hello');

        $payload = json_decode((string) $transport->lastRequest?->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $fixture = json_decode(file_get_contents(__DIR__ . '/../Fixtures/send_text_request.json'), true, 512, JSON_THROW_ON_ERROR);

        self::assertSame($fixture, $payload);
    }
}
