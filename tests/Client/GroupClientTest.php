<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Client;

use BlacklineCloud\SDK\GowaPHP\Client\GroupClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\CreateGroupResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInfoFromLinkResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInfoResponseHydrator;
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
                ['participant' => '628111111111@s.whatsapp.net', 'status' => 'success', 'message' => 'Participant added'],
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
            new GroupInfoResponseHydrator(),
            new GroupInviteLinkResponseHydrator(),
            new SetGroupPhotoResponseHydrator(),
            new GroupParticipantRequestsResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $client->addParticipants('120363347168689807@g.us', '628111111111@s.whatsapp.net', '628222222222@s.whatsapp.net');

        $payload = json_decode((string) $transport->lastRequest?->getBody(), true, 512, JSON_THROW_ON_ERROR);
        self::assertIsArray($payload);
        /** @var list<string> $participants */
        $participants = $payload['participants'] ?? [];
        self::assertSame(['120363347168689807@g.us', '628111111111@s.whatsapp.net', '628222222222@s.whatsapp.net'], [$payload['group_id'], ...$participants]);
    }

    public function testGroupInfo(): void
    {
        $psr17 = new Psr17Factory();
        $body  = json_encode([
            'status'  => 200,
            'code'    => 'SUCCESS',
            'message' => 'Success get group info',
            'results' => [
                'subject'  => 'My Group',
                'owner'    => 'owner@s.whatsapp.net',
                'members'  => 10,
                'settings' => ['announce_only' => false],
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
            new GroupInfoResponseHydrator(),
            new GroupInviteLinkResponseHydrator(),
            new SetGroupPhotoResponseHydrator(),
            new GroupParticipantRequestsResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $info = $client->info('120363347168689807@g.us');

        self::assertSame('My Group', $info->results['subject'] ?? null);
        self::assertSame('https://api.example.test/group/info?group_id=120363347168689807%40g.us', (string) $transport->lastRequest?->getUri());
    }

    public function testExportParticipantsReturnsCsv(): void
    {
        $psr17     = new Psr17Factory();
        $csv       = "jid,name\n123@s.whatsapp.net,Alice\n";
        $transport = new FakeTransport(new Response(200, ['Content-Type' => 'text/csv'], $csv));
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
            new GroupInfoResponseHydrator(),
            new GroupInviteLinkResponseHydrator(),
            new SetGroupPhotoResponseHydrator(),
            new GroupParticipantRequestsResponseHydrator(),
            new GenericResponseHydrator(),
        );

        $csvResult = $client->exportParticipants('120363347168689807@g.us');

        self::assertSame($csv, $csvResult);
        self::assertNotNull($transport->lastRequest);
        self::assertSame('text/csv', $transport->lastRequest->getHeaderLine('Accept'));
        self::assertStringContainsString('/group/participants/export', (string) $transport->lastRequest->getUri());
    }
}
