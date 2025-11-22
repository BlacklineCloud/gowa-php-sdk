<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class ContactPayload
{
    public function __construct(
        public string $displayName,
        public string $vcard,
    ) {
    }
}
