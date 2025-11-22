<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Serialization\Json;
use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    public function testEncodeDecode(): void
    {
        $payload = ['a' => 1, 'b' => ['c' => 'd']];
        $json    = Json::encode($payload);
        $decoded = Json::decode($json);

        self::assertSame($payload, $decoded);
    }
}
