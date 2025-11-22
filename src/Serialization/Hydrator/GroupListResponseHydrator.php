<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\Group;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupListResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupParticipant;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class GroupListResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): GroupListResponse
    {
        $r       = new ArrayReader($payload);
        $results = new ArrayReader($r->requireObject('results'), '$.results');
        $data    = $results->requireObject('data');
        $groups  = [];
        foreach ($data as $row) {
            $rowR         = new ArrayReader((array) $row, '$.results.data');
            $participants = [];
            foreach ($rowR->optionalObject('Participants') ?? [] as $p) {
                $pR             = new ArrayReader((array) $p, '$.results.data.Participants');
                $participants[] = new GroupParticipant(
                    jid: $pR->requireString('JID'),
                    phoneNumber: $pR->requireString('JID'),
                    lid: $pR->optionalString('LID'),
                    displayName: $pR->optionalString('DisplayName'),
                    isAdmin: $pR->requireBool('IsAdmin'),
                    isSuperAdmin: $pR->requireBool('IsSuperAdmin'),
                );
            }

            $disappearingTimer = $rowR->optionalInt('DisappearingTimer') ?? (int) ($rowR->optionalString('DisappearingTimer') ?? 0);

            $groups[] = new Group(
                jid: $rowR->requireString('JID'),
                ownerJid: $rowR->requireString('OwnerJID'),
                name: $rowR->requireString('Name'),
                nameSetAt: $this->optionalDate($rowR->optionalString('NameSetAt')),
                nameSetBy: $rowR->optionalString('NameSetBy'),
                topic: $rowR->optionalString('Topic'),
                topicId: $rowR->optionalString('TopicID'),
                topicSetAt: $this->optionalDate($rowR->optionalString('TopicSetAt')),
                topicSetBy: $rowR->optionalString('TopicSetBy'),
                topicDeleted: $rowR->optionalBool('TopicDeleted') ?? false,
                isLocked: $rowR->optionalBool('IsLocked')         ?? false,
                isAnnounce: $rowR->optionalBool('IsAnnounce')     ?? false,
                announceVersionId: $rowR->optionalString('AnnounceVersionID'),
                isEphemeral: $rowR->optionalBool('IsEphemeral') ?? false,
                disappearingTimer: $disappearingTimer,
                isIncognito: $rowR->optionalBool('IsIncognito') ?? false,
                isParent: $rowR->optionalBool('IsParent')       ?? false,
                defaultMembershipApprovalMode: $rowR->optionalString('DefaultMembershipApprovalMode'),
                linkedParentJid: $rowR->optionalString('LinkedParentJID'),
                isDefaultSubGroup: $rowR->optionalBool('IsDefaultSubGroup')           ?? false,
                isJoinApprovalRequired: $rowR->optionalBool('IsJoinApprovalRequired') ?? false,
                groupCreated: $this->optionalDate($rowR->optionalString('GroupCreated')),
                participantVersionId: $rowR->optionalString('ParticipantVersionID'),
                participants: $participants,
                memberAddMode: $rowR->optionalString('MemberAddMode'),
            );
        }

        return new GroupListResponse(
            code: $r->requireString('code'),
            message: $r->requireString('message'),
            groups: $groups,
        );
    }

    private function optionalDate(?string $value): ?\DateTimeImmutable
    {
        return $value === null || $value === '' ? null : new \DateTimeImmutable($value);
    }
}
