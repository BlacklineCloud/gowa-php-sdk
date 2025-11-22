<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator;

use BlacklineCloud\SDK\GowaPHP\Domain\Dto\LabelChatResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\LabelChatResult;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;

final class LabelChatResponseHydrator implements HydratorInterface
{
    /** @param array<string,mixed> $payload */
    public function hydrate(array $payload): LabelChatResponse
    {
        $r   = new ArrayReader($payload);
        $res = new ArrayReader($r->requireObject('results'), '$.results');

        return new LabelChatResponse(
            $r->requireString('code'),
            $r->requireString('message'),
            new LabelChatResult(
                status: $res->requireString('status'),
                message: $res->requireString('message'),
                chatJid: $res->requireString('chat_jid'),
                labelId: $res->requireString('label_id'),
                labeled: $res->requireBool('labeled'),
            ),
        );
    }
}
