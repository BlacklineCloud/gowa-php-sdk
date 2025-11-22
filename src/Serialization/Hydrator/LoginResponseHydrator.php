<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\LoginResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class LoginResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): LoginResponse
    {
        $reader  = new ArrayReader($payload);
        $code    = $reader->requireString('code');
        $message = $reader->requireString('message');
        $results = new ArrayReader($reader->requireObject('results'), '$.results');

        return new LoginResponse(
            code: $code,
            message: $message,
            qrDuration: $results->requireInt('qr_duration'),
            qrLink: $results->requireString('qr_link'),
        );
    }
}
