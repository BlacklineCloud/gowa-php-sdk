<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Client;

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

final class UserClientTest extends TestCase
{
    public function testInfoBuildsRequest(): void
    {
        $psr17 = new Psr17Factory();
        $body  = json_encode([
            'code'    => 'SUCCESS',
            'message' => 'Success get info',
            'results' => [
                'pushname'      => 'Alice',
                'verified'      => 1,
                'lid'           => null,
                'business_name' => null,
            ],
        ], JSON_THROW_ON_ERROR);
        $response  = new Response(200, ['Content-Type' => 'application/json'], $body);
        $transport = new FakeTransport($response);
        $config    = new ClientConfig('https://api.example.test', 'u', 'p');

        $client = new UserClient(
            $config,
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

        $dto = $client->info();

        self::assertSame('Alice', $dto->user->pushName);
        self::assertSame('https://api.example.test/user/info', (string) $transport->lastRequest?->getUri());
    }

    public function testChangePushName(): void
    {
        $psr17 = new Psr17Factory();
        $body  = json_encode([
            'code'    => 'SUCCESS',
            'message' => 'Pushname updated',
            'results' => [
                'status' => 'ok',
            ],
        ], JSON_THROW_ON_ERROR);
        $response  = new Response(200, ['Content-Type' => 'application/json'], $body);
        $transport = new FakeTransport($response);
        $config    = new ClientConfig('https://api.example.test', 'u', 'p');

        $client = new UserClient(
            $config,
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

        $dto = $client->changePushName('New Name');

        self::assertNotNull($transport->lastRequest);
        $payload = json_decode((string) $transport->lastRequest->getBody(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($payload);
        self::assertSame('https://api.example.test/user/pushname', (string) $transport->lastRequest->getUri());
        self::assertSame('New Name', $payload['push_name'] ?? null);
        self::assertSame('SUCCESS', $dto->code);
    }

    public function testOtherUserEndpoints(): void
    {
        $psr17 = new Psr17Factory();
        $responses = [
            // avatar
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'avatar',
                'results' => [
                    'avatar' => 'http://avatar',
                ],
            ], JSON_THROW_ON_ERROR)),
            // privacy
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'privacy',
                'results' => [
                    'last_seen' => 'all',
                ],
            ], JSON_THROW_ON_ERROR)),
            // contacts
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'contacts',
                'results' => [
                    'data' => [],
                ],
            ], JSON_THROW_ON_ERROR)),
            // business profile
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'business',
                'results' => [
                    'name'        => 'Biz',
                    'description' => 'Desc',
                ],
            ], JSON_THROW_ON_ERROR)),
            // check
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'check',
                'results' => [
                    'is_on_whatsapp' => 1,
                    'exists' => true,
                    'jid'    => '628222@s.whatsapp.net',
                ],
            ], JSON_THROW_ON_ERROR)),
        ];
        $transport = new FakeTransport($responses);
        $config    = new ClientConfig('https://api.example.test', 'u', 'p');

        $client = new UserClient(
            $config,
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

        $client->avatar('628111111111@s.whatsapp.net');
        $client->privacy();
        $client->myContacts();
        $client->businessProfile('628111111111@s.whatsapp.net');
        $client->check('628111');

        self::assertNotNull($transport->lastRequest);
    }
}
