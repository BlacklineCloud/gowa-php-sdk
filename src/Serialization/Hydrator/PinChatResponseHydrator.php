<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\PinChatResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\PinChatResult;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class PinChatResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): PinChatResponse
    {
        $r = new ArrayReader($payload);
        $res = new ArrayReader($r->requireObject('results'), '$.results');

        return new PinChatResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            new PinChatResult(
                status: $res->requireString('status'),
                message: $res->requireString('message'),
                chatJid: $res->requireString('chat_jid'),
                pinned: $res->requireBool('pinned'),
            ),
        );
    }
}
