<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Contract;

use BlacklineCloud\SDK\GowaPHP\Client\UserClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\AvatarResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\BusinessProfileResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\MyContactsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\PrivacyResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\UserCheckResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\UserInfoResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class PushNameRequestContractTest extends TestCase
{
    public function testPayloadMatchesFixture(): void
    {
        $psr17     = new Psr17Factory();
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], json_encode([
            'code'    => 'SUCCESS',
            'message' => 'Pushname updated',
            'results' => ['status' => 'ok'],
        ], JSON_THROW_ON_ERROR)));

        $client = new UserClient(
            new ClientConfig('https://api.example.test', 'u', 'p'),
            $transport,
            $psr17,
            $psr17,
            new UserInfoResponseHydrator(),
            new AvatarResponseHydrator(),
            new PrivacyResponseHydrator(),
            new MyContactsResponseHydrator(),
            new BusinessProfileResponseHydrator(),
            new UserCheckResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $client->changePushName('New Name');

        $payload = json_decode((string) $transport->lastRequest?->getBody(), true, 512, JSON_THROW_ON_ERROR);
        $fixture = json_decode((string) file_get_contents(__DIR__ . '/../Fixtures/change_pushname_request.json'), true, 512, JSON_THROW_ON_ERROR);
        self::assertSame($fixture, $payload);
        self::assertSame('https://api.example.test/user/pushname', (string) $transport->lastRequest?->getUri());
    }
}
