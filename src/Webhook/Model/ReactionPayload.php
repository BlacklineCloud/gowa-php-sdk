<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class ReactionPayload
{
    public function __construct(
        public string $message,
        public string $id,
    ) {
    }
}
