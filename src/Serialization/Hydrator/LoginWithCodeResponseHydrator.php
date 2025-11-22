<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\LoginWithCodeResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class LoginWithCodeResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): LoginWithCodeResponse
    {
        $r       = new ArrayReader($payload);
        $results = new ArrayReader($r->requireObject('results'), '$.results');

        return new LoginWithCodeResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            $results->requireString('pair_code'),
        );
    }
}
