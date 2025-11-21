<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\SendResponse;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class SendResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): SendResponse
    {
        $reader = new ArrayReader($payload);
        $code = $reader->requireString('code');
        $message = $reader->requireString('message');
        $results = new ArrayReader($reader->requireObject('results'), '$.results');

        return new SendResponse(
            code: $code,
            message: $message,
            messageId: $results->requireString('message_id'),
            status: $results->requireString('status'),
        );
    }
}
