<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Client;

use BlacklineCloud\SDK\GowaPHP\Client\AppClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\DevicesResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\LoginWithCodeResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class AppClientTest extends TestCase
{
    public function testLoginUsesBaseUri(): void
    {
        $psr17 = new Psr17Factory();
        $json  = json_encode([
            'code'    => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                'qr_duration' => 30,
                'qr_link'     => 'http://localhost/qr.png',
            ],
        ], JSON_THROW_ON_ERROR);
        $response  = new Response(200, ['Content-Type' => 'application/json'], $json);
        $transport = new FakeTransport($response);
        $config    = new ClientConfig('https://api.example.test', 'u', 'p', basePath: '/gowa');

        $client = new AppClient(
            $config,
            $transport,
            $psr17,
            $psr17,
            new LoginResponseHydrator(),
            new LoginWithCodeResponseHydrator(),
            new DevicesResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $dto = $client->login();

        self::assertSame('SUCCESS', $dto->code);
        self::assertSame('https://api.example.test/gowa/app/login', (string) $transport->lastRequest?->getUri());
    }

    public function testOtherAppEndpoints(): void
    {
        $psr17  = new Psr17Factory();
        $config = new ClientConfig('https://api.example.test', 'u', 'p');

        $responses = [
            // login with code
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'pair',
                'results' => ['pair_code' => '1234'],
            ], JSON_THROW_ON_ERROR)),
            // logout
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'logout',
                'results' => null,
            ], JSON_THROW_ON_ERROR)),
            // reconnect
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'reconnect',
                'results' => null,
            ], JSON_THROW_ON_ERROR)),
            // devices
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'devices',
                'results' => [
                    'devices' => [],
                ],
            ], JSON_THROW_ON_ERROR)),
        ];

        $transport = new \BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport($responses);

        $client = new AppClient(
            $config,
            $transport,
            $psr17,
            $psr17,
            new LoginResponseHydrator(),
            new LoginWithCodeResponseHydrator(),
            new DevicesResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $client->loginWithCode('628123456789');
        $client->logout();
        $client->reconnect();
        $client->devices();

        self::assertNotNull($transport->lastRequest);
    }
}
