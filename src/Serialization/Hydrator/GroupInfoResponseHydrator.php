<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GroupInfoResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class GroupInfoResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): GroupInfoResponse
    {
        $reader  = new ArrayReader($payload);
        $results = $reader->requireObject('results');

        return new GroupInfoResponse(
            status: $reader->requireInt('status'),
            code: $reader->requireString('code'),
            message: $reader->requireString('message'),
            results: $results,
        );
    }
}
