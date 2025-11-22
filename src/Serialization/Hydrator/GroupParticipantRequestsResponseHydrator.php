<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupParticipantRequest;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupParticipantRequestsResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class GroupParticipantRequestsResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): GroupParticipantRequestsResponse
    {
        $r        = new ArrayReader($payload);
        $results  = new ArrayReader($r->requireObject('results'), '$.results');
        $requests = [];
        foreach ($results->requireObject('data') as $row) {
            $rowR       = new ArrayReader((array) $row, '$.results.data');
            $requests[] = new GroupParticipantRequest(
                jid: $rowR->requireString('jid'),
                requestedAt: new \DateTimeImmutable($rowR->requireString('requested_at')),
            );
        }

        return new GroupParticipantRequestsResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            $requests,
        );
    }
}
