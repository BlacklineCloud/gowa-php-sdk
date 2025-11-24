<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Serialization;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;
use BlacklineCloud\SDK\GowaPHP\Serialization\ArrayReader;
use PHPUnit\Framework\TestCase;

final class ArrayReaderTest extends TestCase
{
    public function testRequireAndOptional(): void
    {
        $reader = new ArrayReader([
            'string' => 'value',
            'int'    => 1,
            'float'  => 1.5,
            'bool'   => true,
        ]);

        self::assertSame('value', $reader->requireString('string'));
        self::assertSame(1, $reader->requireInt('int'));
        self::assertSame(1.5, $reader->requireFloat('float'));
        self::assertTrue($reader->requireBool('bool'));
        self::assertNull($reader->optionalString('missing'));
        self::assertNull($reader->optionalFloat('missing'));
    }

    public function testValidationFailure(): void
    {
        $reader = new ArrayReader(['string' => 123]);
        $this->expectException(ValidationException::class);
        $reader->requireString('string');
    }

    public function testOptionalObjectRejectsNonArray(): void
    {
        $reader = new ArrayReader(['obj' => 'nope']);
        $this->expectException(ValidationException::class);
        $reader->optionalObject('obj');
    }
}
