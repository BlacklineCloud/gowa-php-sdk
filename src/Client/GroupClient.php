<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Client;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\CreateGroupResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GenericResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupInfoFromLinkResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupInfoResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupInviteLinkResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupListResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupParticipantRequestsResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupParticipantsResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\ManageParticipantResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\SetGroupPhotoResponse;
use BlacklineCloud\SDK\GowaPHP\Http\ApiClient;
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
use BlacklineCloud\SDK\GowaPHP\Support\InputValidator;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class GroupClient extends ApiClient
{
    public function __construct(
        ClientConfig $config,
        HttpTransportInterface $transport,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        private readonly CreateGroupResponseHydrator $createHydrator,
        private readonly GroupListResponseHydrator $listHydrator,
        private readonly GroupParticipantsResponseHydrator $participantsHydrator,
        private readonly ManageParticipantResponseHydrator $manageHydrator,
        private readonly GroupInfoFromLinkResponseHydrator $infoFromLinkHydrator,
        private readonly GroupInfoResponseHydrator $infoHydrator,
        private readonly GroupInviteLinkResponseHydrator $inviteHydrator,
        private readonly SetGroupPhotoResponseHydrator $photoHydrator,
        private readonly GroupParticipantRequestsResponseHydrator $participantRequestsHydrator,
        private readonly GenericResponseHydrator $genericHydrator,
    ) {
        parent::__construct($config, $transport, $requestFactory, $streamFactory);
    }

    public function create(string $subject, string ...$participants): CreateGroupResponse
    {
        $validated = array_map([InputValidator::class, 'jid'], $participants);

        return $this->createHydrator->hydrate($this->post('/group', [
            'subject'      => $subject,
            'participants' => $validated,
        ]));
    }

    public function list(): GroupListResponse
    {
        return $this->listHydrator->hydrate($this->get('/user/my/groups'));
    }

    public function info(string $groupId): GroupInfoResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->infoHydrator->hydrate($this->get('/group/info', ['group_id' => $gid]));
    }

    public function participants(string $groupId): GroupParticipantsResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->participantsHydrator->hydrate($this->get('/group/participants', ['group_id' => $gid]));
    }

    public function addParticipants(string $groupId, string ...$participants): ManageParticipantResponse
    {
        $gid       = InputValidator::jid($groupId);
        $validated = array_map([InputValidator::class, 'jid'], $participants);

        return $this->manageHydrator->hydrate($this->post('/group/participants', [
            'group_id'     => $gid,
            'participants' => $validated,
        ]));
    }

    public function removeParticipants(string $groupId, string ...$participants): ManageParticipantResponse
    {
        $gid       = InputValidator::jid($groupId);
        $validated = array_map([InputValidator::class, 'jid'], $participants);

        return $this->manageHydrator->hydrate($this->post('/group/participants/remove', [
            'group_id'     => $gid,
            'participants' => $validated,
        ]));
    }

    public function promoteParticipants(string $groupId, string ...$participants): ManageParticipantResponse
    {
        $gid       = InputValidator::jid($groupId);
        $validated = array_map([InputValidator::class, 'jid'], $participants);

        return $this->manageHydrator->hydrate($this->post('/group/participants/promote', [
            'group_id'     => $gid,
            'participants' => $validated,
        ]));
    }

    public function demoteParticipants(string $groupId, string ...$participants): ManageParticipantResponse
    {
        $gid       = InputValidator::jid($groupId);
        $validated = array_map([InputValidator::class, 'jid'], $participants);

        return $this->manageHydrator->hydrate($this->post('/group/participants/demote', [
            'group_id'     => $gid,
            'participants' => $validated,
        ]));
    }

    public function joinWithLink(string $link): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/group/join-with-link', ['invite_link' => $link]));
    }

    public function infoFromLink(string $link): GroupInfoFromLinkResponse
    {
        return $this->infoFromLinkHydrator->hydrate($this->get('/group/info-from-link', ['invite_link' => $link]));
    }

    public function inviteLink(string $groupId): GroupInviteLinkResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->inviteHydrator->hydrate($this->get('/group/invite-link', ['group_id' => $gid]));
    }

    public function setPhoto(string $groupId, string $base64Image): SetGroupPhotoResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->photoHydrator->hydrate($this->post('/group/photo', [
            'group_id' => $gid,
            'image'    => $base64Image,
        ]));
    }

    public function setName(string $groupId, string $name): GenericResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->genericHydrator->hydrate($this->post('/group/name', [
            'group_id' => $gid,
            'name'     => $name,
        ]));
    }

    public function setLocked(string $groupId, bool $locked): GenericResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->genericHydrator->hydrate($this->post('/group/locked', [
            'group_id' => $gid,
            'locked'   => $locked,
        ]));
    }

    public function setAnnounce(string $groupId, bool $announce): GenericResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->genericHydrator->hydrate($this->post('/group/announce', [
            'group_id' => $gid,
            'announce' => $announce,
        ]));
    }

    public function setTopic(string $groupId, string $topic): GenericResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->genericHydrator->hydrate($this->post('/group/topic', [
            'group_id' => $gid,
            'topic'    => $topic,
        ]));
    }

    public function participantRequests(string $groupId): GroupParticipantRequestsResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->participantRequestsHydrator->hydrate($this->get('/group/participant-requests', [
            'group_id' => $gid,
        ]));
    }

    public function exportParticipants(string $groupId): string
    {
        $gid     = InputValidator::jid($groupId);
        $uri     = $this->buildUri('/group/participants/export', ['group_id' => $gid]);
        $request = $this->requestFactory->createRequest('GET', $uri)
            ->withHeader('Accept', 'text/csv');

        $response = $this->transport->sendRequest($request);

        return (string) $response->getBody();
    }

    public function approveRequest(string $groupId, string ...$participants): GenericResponse
    {
        $gid       = InputValidator::jid($groupId);
        $validated = array_map([InputValidator::class, 'jid'], $participants);

        return $this->genericHydrator->hydrate($this->post('/group/participant-requests/approve', [
            'group_id'     => $gid,
            'participants' => $validated,
        ]));
    }

    public function rejectRequest(string $groupId, string ...$participants): GenericResponse
    {
        $gid       = InputValidator::jid($groupId);
        $validated = array_map([InputValidator::class, 'jid'], $participants);

        return $this->genericHydrator->hydrate($this->post('/group/participant-requests/reject', [
            'group_id'     => $gid,
            'participants' => $validated,
        ]));
    }

    public function leave(string $groupId): GenericResponse
    {
        $gid = InputValidator::jid($groupId);

        return $this->genericHydrator->hydrate($this->post('/group/leave', ['group_id' => $gid]));
    }
}
