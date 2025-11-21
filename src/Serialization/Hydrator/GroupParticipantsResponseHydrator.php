<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupParticipant;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupParticipants;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupParticipantsResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class GroupParticipantsResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): GroupParticipantsResponse
    {
        $reader = new ArrayReader($payload);
        $resReader = new ArrayReader($reader->requireObject('results'), '$.results');
        $participantsRaw = $resReader->requireObject('participants');
        $participants = [];
        foreach ($participantsRaw as $row) {
            $rowReader = new ArrayReader((array) $row, '$.results.participants');
            $participants[] = new GroupParticipant(
                jid: $rowReader->requireString('jid'),
                phoneNumber: $rowReader->requireString('phone_number'),
                lid: $rowReader->optionalString('lid'),
                displayName: $rowReader->optionalString('display_name'),
                isAdmin: $rowReader->requireBool('is_admin'),
                isSuperAdmin: $rowReader->requireBool('is_super_admin'),
            );
        }

        $result = new GroupParticipants(
            groupId: $resReader->requireString('group_id'),
            name: $resReader->requireString('name'),
            participants: $participants,
        );

        return new GroupParticipantsResponse(
            code: $reader->requireString('code'),
            message: $reader->requireString('message'),
            results: $result,
        );
    }
}
