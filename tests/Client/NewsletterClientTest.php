<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Client;

use BlacklineCloud\SDK\GowaPHP\Client\NewsletterClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\NewsletterResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class NewsletterClientTest extends TestCase
{
    public function testListNewsletters(): void
    {
        $psr17 = new Psr17Factory();
        $body  = json_encode([
            'code'    => 'SUCCESS',
            'message' => 'Success get list newsletter',
            'results' => [
                'data' => [
                    [
                        'id'              => 'nid',
                        'state'           => ['type' => 'active'],
                        'thread_metadata' => [
                            'name'              => ['text' => 'Channel'],
                            'description'       => ['text' => 'desc'],
                            'subscribers_count' => '1',
                        ],
                    ],
                ],
            ],
        ], JSON_THROW_ON_ERROR);
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], $body));
        $client    = new NewsletterClient(
            new ClientConfig('https://api.example.test', 'u', 'p'),
            $transport,
            $psr17,
            $psr17,
            new NewsletterResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $dto = $client->list();

        self::assertSame('nid', $dto->newsletters[0]->id);
        self::assertSame('https://api.example.test/user/my/newsletters', (string) $transport->lastRequest?->getUri());
    }
}
