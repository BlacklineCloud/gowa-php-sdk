<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support\Media;

use Psr\Http\Message\StreamInterface;

final class StreamMediaUpload implements MediaUploadInterface
{
    private string $path;

    public function __construct(private readonly StreamInterface $stream, ?string $extension = null)
    {
        $tmp = tempnam(sys_get_temp_dir(), 'gowa_media_');
        if ($tmp === false) {
            throw new \RuntimeException('Unable to create temporary file for media upload');
        }

        if ($extension !== null && $extension !== '') {
            $candidate = $tmp . '.' . ltrim($extension, '.');
            if (!@rename($tmp, $candidate)) {
                // fall back to original temp name
                $candidate = $tmp;
            }
            $tmp = $candidate;
        }

        $this->path = $tmp;
        $this->writeStream($stream);
    }

    public function toPath(): string
    {
        return $this->path;
    }

    public function __destruct()
    {
        if (is_file($this->path)) {
            @unlink($this->path);
        }
    }

    private function writeStream(StreamInterface $stream): void
    {
        if ($stream->isSeekable()) {
            $stream->rewind();
        }

        $bytes   = $stream->getContents();
        $written = file_put_contents($this->path, $bytes);
        if ($written === false) {
            throw new \RuntimeException('Unable to write media stream to temporary file');
        }
    }
}
