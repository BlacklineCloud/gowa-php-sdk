<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Support\Media;

use BlacklineCloud\SDK\GowaPHP\Support\Media\StreamMediaUpload;
use Nyholm\Psr7\Stream;
use PHPUnit\Framework\TestCase;

final class StreamMediaUploadTest extends TestCase
{
    public function testWritesStreamToTempFile(): void
    {
        $stream = Stream::create('hello world');
        $upload = new StreamMediaUpload($stream, 'txt');

        $path = $upload->toPath();
        self::assertFileExists($path);
        self::assertStringContainsString('gowa_media_', basename($path));
        self::assertSame('hello world', file_get_contents($path));
    }
}
