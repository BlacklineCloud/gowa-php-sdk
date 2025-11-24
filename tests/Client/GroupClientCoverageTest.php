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

final class GroupClientCoverageTest extends TestCase
{
    private GroupClient $client;

    protected function setUp(): void
    {
        $psr17 = new Psr17Factory();
        $config = new ClientConfig('https://api.example.test', 'u', 'p');

        $responses = [
            // create
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'created',
                'results' => [
                    'group_id'     => '120@g.us',
                    'participants' => [],
                ],
            ], JSON_THROW_ON_ERROR)),
            // participants
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => [
                    'group_id'     => '120@g.us',
                    'name'         => 'Group',
                    'participants' => [
                        [
                            'jid'           => '120@s.whatsapp.net',
                            'phone_number'  => '120',
                            'lid'           => null,
                            'display_name'  => 'Member',
                            'is_admin'      => false,
                            'is_super_admin'=> false,
                        ],
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
            // manage participant (add/remove/promote/demote share payload)
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => [
                    ['participant' => '120@s.whatsapp.net', 'status' => 'added', 'message' => 'ok'],
                ],
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => [
                    ['participant' => '120@s.whatsapp.net', 'status' => 'removed', 'message' => 'ok'],
                ],
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => [
                    ['participant' => '120@s.whatsapp.net', 'status' => 'promoted', 'message' => 'ok'],
                ],
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => [
                    ['participant' => '120@s.whatsapp.net', 'status' => 'demoted', 'message' => 'ok'],
                ],
            ], JSON_THROW_ON_ERROR)),
            // join via link
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'joined',
                'results' => null,
            ], JSON_THROW_ON_ERROR)),
            // info-from-link
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'info',
                'results' => [
                    'group_id'          => '120@g.us',
                    'name'              => 'Group',
                    'topic'             => 'Topic',
                    'created_at'        => '2024-01-01T00:00:00Z',
                    'participant_count' => 1,
                    'is_locked'         => false,
                    'is_announce'       => false,
                    'is_ephemeral'      => false,
                    'description'       => null,
                ],
            ], JSON_THROW_ON_ERROR)),
            // invite link
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'link',
                'results' => [
                    'invite_link' => 'https://invite',
                    'group_id'    => '120@g.us',
                ],
            ], JSON_THROW_ON_ERROR)),
            // info
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'status'  => 200,
                'code'    => 'SUCCESS',
                'message' => 'info',
                'results' => ['subject' => 'Group'],
            ], JSON_THROW_ON_ERROR)),
            // photo
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'photo',
                'results' => [
                    'picture_id' => 'pic123',
                    'message'    => 'ok',
                ],
            ], JSON_THROW_ON_ERROR)),
            // participant requests
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'requests',
                'results' => [
                    'data' => [
                        [
                            'jid'          => '120@s.whatsapp.net',
                            'requested_at' => '2024-01-02T00:00:00Z',
                        ],
                    ],
                ],
            ], JSON_THROW_ON_ERROR)),
            // approve/reject/leave/name/locked/announce/topic generic responses
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => null,
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => null,
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => null,
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => null,
            ], JSON_THROW_ON_ERROR)),
            new Response(200, ['Content-Type' => 'application/json'], json_encode([
                'code'    => 'SUCCESS',
                'message' => 'ok',
                'results' => null,
            ], JSON_THROW_ON_ERROR)),
        ];

        $transport = new FakeTransport($responses);

        $this->client = new GroupClient(
            $config,
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
    }

    public function testGroupFlows(): void
    {
        $this->client->create('Subject', '628111111111@s.whatsapp.net');
        $this->client->participants('120@g.us');
        $this->client->addParticipants('120@g.us', '120@s.whatsapp.net');
        $this->client->removeParticipants('120@g.us', '120@s.whatsapp.net');
        $this->client->promoteParticipants('120@g.us', '120@s.whatsapp.net');
        $this->client->demoteParticipants('120@g.us', '120@s.whatsapp.net');
        $this->client->joinWithLink('https://invite');
        $this->client->infoFromLink('https://invite');
        $this->client->inviteLink('120@g.us');
        $this->client->info('120@g.us');
        $this->client->setPhoto('120@g.us', base64_encode('img'));
        $this->client->participantRequests('120@g.us');
        $this->client->approveRequest('120@g.us', '120@s.whatsapp.net');
        $this->client->rejectRequest('120@g.us', '120@s.whatsapp.net');
        $this->client->leave('120@g.us');
        $this->client->setName('120@g.us', 'New');
        $this->client->setLocked('120@g.us', true);
        $this->client->setAnnounce('120@g.us', true);
        $this->client->setTopic('120@g.us', 'topic');

        $this->assertTrue(true); // reached without exceptions
    }
}
