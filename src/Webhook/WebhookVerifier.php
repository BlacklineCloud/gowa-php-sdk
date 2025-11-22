<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Webhook;

use BlacklineCloud\SDK\GowaPHP\Domain\Value\WebhookSignature;
use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final class WebhookVerifier
{
    public function __construct(private readonly string $secret)
    {
    }

    /**
     * Verify signature header value (sha256=...) against raw payload.
     */
    public function verify(string $rawPayload, string $signatureHeader): bool
    {
        try {
            $signature = new WebhookSignature($signatureHeader);
        } catch (ValidationException) {
            return false;
        }
        $computed = hash_hmac('sha256', $rawPayload, $this->secret);

        return hash_equals($computed, $signature->hex());
    }
}
