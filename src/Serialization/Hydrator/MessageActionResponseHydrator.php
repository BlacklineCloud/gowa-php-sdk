<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\MessageActionResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\MessageActionResult;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class MessageActionResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): MessageActionResponse
    {
        $r   = new ArrayReader($payload);
        $res = new ArrayReader($r->requireObject('results'), '$.results');

        return new MessageActionResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            new MessageActionResult(
                status: $res->requireString('status'),
                message: $res->requireString('message'),
                messageId: $res->requireString('message_id'),
            ),
        );
    }
}
