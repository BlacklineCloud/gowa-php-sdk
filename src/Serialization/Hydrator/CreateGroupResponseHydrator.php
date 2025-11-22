<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\CreateGroupResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class CreateGroupResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): CreateGroupResponse
    {
        $r   = new ArrayReader($payload);
        $res = new ArrayReader($r->requireObject('results'), '$.results');

        return new CreateGroupResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            $res->requireString('group_id'),
        );
    }
}
