<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Support;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;
use BlacklineCloud\SDK\GowaPHP\Support\Media\MediaInput;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\TestCase;

final class MediaInputTest extends TestCase
{
    public function testFromPath(): void
    {
        $factory = new Psr17Factory();
        $tmp = tempnam(sys_get_temp_dir(), 'media');
        file_put_contents($tmp, 'hello');

        $media = MediaInput::fromPath($tmp, $factory, 'text/plain');

        self::assertSame('text/plain', $media->mimeType);
        self::assertSame(basename($tmp), $media->filename);
    }

    public function testInvalidPathThrows(): void
    {
        $factory = new Psr17Factory();
        $this->expectException(ValidationException::class);
        MediaInput::fromPath('/nope', $factory);
    }
}
