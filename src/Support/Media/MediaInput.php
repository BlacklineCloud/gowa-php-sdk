<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support\Media;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;

final readonly class MediaInput
{
    public function __construct(
        public StreamInterface $stream,
        public string $filename,
        public string $mimeType,
    ) {
        if ($this->filename === '') {
            throw new ValidationException('Filename must not be empty');
        }

        if ($this->mimeType === '') {
            throw new ValidationException('MIME type must not be empty');
        }
    }

    public static function fromStream(StreamInterface $stream, string $filename, string $mimeType): self
    {
        return new self($stream, $filename, $mimeType);
    }

    /**
     * @param resource $resource
     */
    public static function fromResource(mixed $resource, string $filename, string $mimeType, StreamFactoryInterface $factory): self
    {
        if (!\is_resource($resource)) {
            throw new ValidationException('Expected stream resource');
        }

        return new self($factory->createStreamFromResource($resource), $filename, $mimeType);
    }

    public static function fromPath(string $path, StreamFactoryInterface $factory, ?string $mimeType = null): self
    {
        if (!is_file($path) || !is_readable($path)) {
            throw new ValidationException('Media path is not readable: ' . $path);
        }

        $stream   = $factory->createStreamFromFile($path, 'r');
        $filename = basename($path);
        $mime     = $mimeType ?? self::detectMime($path) ?? 'application/octet-stream';

        return new self($stream, $filename, $mime);
    }

    private static function detectMime(string $path): ?string
    {
        if (function_exists('mime_content_type')) {
            $mime = @mime_content_type($path);
            if (is_string($mime) && $mime !== '') {
                return $mime;
            }
        }

        if (class_exists(\finfo::class)) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime  = $finfo->file($path);
            if (is_string($mime) && $mime !== '') {
                return $mime;
            }
        }

        return null;
    }
}
