<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupInviteLinkResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class GroupInviteLinkResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): GroupInviteLinkResponse
    {
        $r = new ArrayReader($payload);
        $res = new ArrayReader($r->requireObject('results'), '$.results');

        return new GroupInviteLinkResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            $res->requireString('invite_link'),
            $res->requireString('group_id'),
        );
    }
}
