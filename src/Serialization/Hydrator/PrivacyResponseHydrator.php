<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\PrivacyResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\PrivacySettings;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class PrivacyResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): PrivacyResponse
    {
        $reader = new ArrayReader($payload);
        $code = $reader->requireString('code');
        $message = $reader->requireString('message');
        $results = new ArrayReader($reader->requireObject('results'), '$.results');

        return new PrivacyResponse(
            $code,
            $message,
            new PrivacySettings(
                groupAdd: $results->optionalString('group_add'),
                lastSeen: $results->optionalString('last_seen'),
                status: $results->optionalString('status'),
                profile: $results->optionalString('profile'),
                readReceipts: $results->optionalString('read_receipts'),
            ),
        );
    }
}
