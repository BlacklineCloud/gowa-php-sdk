<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Client;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\CreateGroupResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GenericResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupInfoFromLinkResponse;
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
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupInviteLinkResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupListResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupParticipantRequestsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GroupParticipantsResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\ManageParticipantResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SetGroupPhotoResponseHydrator;
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
        private readonly GroupInviteLinkResponseHydrator $inviteHydrator,
        private readonly SetGroupPhotoResponseHydrator $photoHydrator,
        private readonly GroupParticipantRequestsResponseHydrator $participantRequestsHydrator,
        private readonly GenericResponseHydrator $genericHydrator,
    ) {
        parent::__construct($config, $transport, $requestFactory, $streamFactory);
    }

    public function create(string $subject, string ...$participants): CreateGroupResponse
    {
        return $this->createHydrator->hydrate($this->post('/group', [
            'subject' => $subject,
            'participants' => $participants,
        ]));
    }

    public function list(): GroupListResponse
    {
        return $this->listHydrator->hydrate($this->get('/user/my/groups'));
    }

    public function participants(string $groupId): GroupParticipantsResponse
    {
        return $this->participantsHydrator->hydrate($this->get('/group/participants', ['group_id' => $groupId]));
    }

    public function addParticipants(string $groupId, string ...$participants): ManageParticipantResponse
    {
        return $this->manageHydrator->hydrate($this->post('/group/participants', [
            'group_id' => $groupId,
            'participants' => $participants,
        ]));
    }

    public function removeParticipants(string $groupId, string ...$participants): ManageParticipantResponse
    {
        return $this->manageHydrator->hydrate($this->post('/group/participants/remove', [
            'group_id' => $groupId,
            'participants' => $participants,
        ]));
    }

    public function promoteParticipants(string $groupId, string ...$participants): ManageParticipantResponse
    {
        return $this->manageHydrator->hydrate($this->post('/group/participants/promote', [
            'group_id' => $groupId,
            'participants' => $participants,
        ]));
    }

    public function demoteParticipants(string $groupId, string ...$participants): ManageParticipantResponse
    {
        return $this->manageHydrator->hydrate($this->post('/group/participants/demote', [
            'group_id' => $groupId,
            'participants' => $participants,
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
        return $this->inviteHydrator->hydrate($this->get('/group/invite-link', ['group_id' => $groupId]));
    }

    public function setPhoto(string $groupId, string $base64Image): SetGroupPhotoResponse
    {
        return $this->photoHydrator->hydrate($this->post('/group/photo', [
            'group_id' => $groupId,
            'image' => $base64Image,
        ]));
    }

    public function setName(string $groupId, string $name): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/group/name', [
            'group_id' => $groupId,
            'name' => $name,
        ]));
    }

    public function setLocked(string $groupId, bool $locked): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/group/locked', [
            'group_id' => $groupId,
            'locked' => $locked,
        ]));
    }

    public function setAnnounce(string $groupId, bool $announce): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/group/announce', [
            'group_id' => $groupId,
            'announce' => $announce,
        ]));
    }

    public function setTopic(string $groupId, string $topic): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/group/topic', [
            'group_id' => $groupId,
            'topic' => $topic,
        ]));
    }

    public function participantRequests(string $groupId): GroupParticipantRequestsResponse
    {
        return $this->participantRequestsHydrator->hydrate($this->get('/group/participant-requests', [
            'group_id' => $groupId,
        ]));
    }

    public function approveRequest(string $groupId, string ...$participants): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/group/participant-requests/approve', [
            'group_id' => $groupId,
            'participants' => $participants,
        ]));
    }

    public function rejectRequest(string $groupId, string ...$participants): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/group/participant-requests/reject', [
            'group_id' => $groupId,
            'participants' => $participants,
        ]));
    }

    public function leave(string $groupId): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/group/leave', ['group_id' => $groupId]));
    }
}
