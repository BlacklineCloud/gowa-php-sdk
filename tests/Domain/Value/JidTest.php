<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Domain\Value;

use BlacklineCloud\SDK\GowaPHP\Domain\Value\Jid;
use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;
use PHPUnit\Framework\TestCase;

final class JidTest extends TestCase
{
    public function testValidJid(): void
    {
        $jid = new Jid('628123456789@s.whatsapp.net');
        self::assertSame('628123456789@s.whatsapp.net', (string) $jid);
    }

    public function testInvalidJidThrows(): void
    {
        $this->expectException(ValidationException::class);
        new Jid('invalid');
    }
}
