<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\SetGroupPhotoResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class SetGroupPhotoResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): SetGroupPhotoResponse
    {
        $r   = new ArrayReader($payload);
        $res = new ArrayReader($r->requireObject('results'), '$.results');

        return new SetGroupPhotoResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            $res->requireString('picture_id'),
            $res->requireString('message'),
        );
    }
}
