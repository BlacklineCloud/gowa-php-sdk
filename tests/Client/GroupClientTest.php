<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Client;

use BlacklineCloud\SDK\GowaPHP\Client\GroupClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\CreateGroupResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInfoFromLinkResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInviteLinkResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupListResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupParticipantRequestsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupParticipantsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ManageParticipantResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SetGroupPhotoResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Tests\Support\FakeTransport;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class GroupClientTest extends TestCase
{
    public function testAddParticipantsVariadic(): void
    {
        $psr17 = new Psr17Factory();
        $body  = json_encode([
            'code'    => 'SUCCESS',
            'message' => 'Success',
            'results' => [
                ['participant' => 'a@s.whatsapp.net', 'status' => 'success', 'message' => 'Participant added'],
            ],
        ], JSON_THROW_ON_ERROR);
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'application/json'], $body));
        $client    = new GroupClient(
            new ClientConfig('https://api.example.test', 'u', 'p'),
            $transport,
            $psr17,
            $psr17,
            new CreateGroupResponseHydrator(),
            new GroupListResponseHydrator(),
            new GroupParticipantsResponseHydrator(),
            new ManageParticipantResponseHydrator(),
            new GroupInfoFromLinkResponseHydrator(),
            new GroupInviteLinkResponseHydrator(),
            new SetGroupPhotoResponseHydrator(),
            new GroupParticipantRequestsResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $client->addParticipants('gid', 'a@s.whatsapp.net', 'b@s.whatsapp.net');

        $payload = json_decode((string) $transport->lastRequest?->getBody(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($payload);
        self::assertIsArray($payload['participants'] ?? null);
        self::assertSame(['gid', 'a@s.whatsapp.net', 'b@s.whatsapp.net'], [$payload['group_id'], ...$payload['participants']]);
    }
}
