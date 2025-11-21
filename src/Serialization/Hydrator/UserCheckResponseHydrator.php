<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\UserCheckResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class UserCheckResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): UserCheckResponse
    {
        $r = new ArrayReader($payload);
        $res = new ArrayReader($r->requireObject('results'), '$.results');

        return new UserCheckResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            (bool) $res->requireInt('is_on_whatsapp'),
        );
    }
}
