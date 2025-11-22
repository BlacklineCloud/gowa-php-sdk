<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Support\Media;

use BlacklineCloud\SDK\GowaPHP\Support\Media\FileMediaUpload;
use PHPUnit\Framework\TestCase;
use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final class FileMediaUploadTest extends TestCase
{
    public function testValidatesReadableFile(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'gowa_media_test_');
        self::assertNotFalse($tmp);
        file_put_contents($tmp, 'hi');

        $upload = new FileMediaUpload($tmp);
        self::assertSame($tmp, $upload->toPath());
        self::assertSame(2, $upload->size());
        self::assertNotNull($upload->mimeType());
    }

    public function testRejectsMissingFile(): void
    {
        $this->expectException(ValidationException::class);
        new FileMediaUpload('/nonexistent/path/file.bin');
    }

    public function testRejectsEmptyFile(): void
    {
        $tmp = tempnam(sys_get_temp_dir(), 'gowa_media_test_empty_');
        self::assertNotFalse($tmp);
        $this->expectException(ValidationException::class);
        new FileMediaUpload($tmp);
    }
}
