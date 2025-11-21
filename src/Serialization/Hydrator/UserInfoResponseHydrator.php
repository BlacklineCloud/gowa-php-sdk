<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\UserInfo;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\UserInfoResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class UserInfoResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): UserInfoResponse
    {
        $reader = new ArrayReader($payload);
        $code = $reader->requireString('code');
        $message = $reader->requireString('message');
        $results = new ArrayReader($reader->requireObject('results'), '$.results');

        return new UserInfoResponse(
            $code,
            $message,
            new UserInfo(
                pushName: $results->requireString('pushname'),
                verified: (bool) $results->requireInt('verified'),
                lid: $results->optionalString('lid'),
                businessName: $results->optionalString('business_name'),
            ),
        );
    }
}
