<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook\Model;

final readonly class MediaPayload
{
    public function __construct(
        public string $mediaPath,
        public string $mimeType,
        public ?string $caption,
    ) {
    }
}
