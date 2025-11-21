<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupInfoFromLink;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupInfoFromLinkResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class GroupInfoFromLinkResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): GroupInfoFromLinkResponse
    {
        $reader = new ArrayReader($payload);
        $res = new ArrayReader($reader->requireObject('results'), '$.results');

        return new GroupInfoFromLinkResponse(
            $reader->requireString('code'),
            $reader->requireString('message'),
            new GroupInfoFromLink(
                groupId: $res->requireString('group_id'),
                name: $res->requireString('name'),
                topic: $res->requireString('topic'),
                createdAt: new \DateTimeImmutable($res->requireString('created_at')),
                participantCount: $res->requireInt('participant_count'),
                isLocked: $res->requireBool('is_locked'),
                isAnnounce: $res->requireBool('is_announce'),
                isEphemeral: $res->requireBool('is_ephemeral'),
                description: $res->optionalString('description'),
            ),
        );
    }
}
