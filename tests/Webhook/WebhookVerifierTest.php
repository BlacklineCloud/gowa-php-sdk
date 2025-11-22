<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Webhook;

use BlacklineCloud\SDK\GowaPHP\Webhook\WebhookVerifier;
use PHPUnit\Framework\TestCase;

final class WebhookVerifierTest extends TestCase
{
    public function testVerifiesSignature(): void
    {
        $payload = 'test-payload';
        $secret = 'secret';
        $sig = 'sha256=' . hash_hmac('sha256', $payload, $secret);

        $verifier = new WebhookVerifier($secret);

        self::assertTrue($verifier->verify($payload, $sig));
        self::assertFalse($verifier->verify($payload, 'sha256=deadbeef'));
    }
}
