<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support\Media;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final class FileMediaUpload implements MediaUploadInterface
{
    public function __construct(private readonly string $path)
    {
        if ($path === '') {
            throw new ValidationException('Media path must not be empty');
        }

        if (!is_readable($path)) {
            throw new ValidationException('Media file not readable: ' . $path);
        }

        $size = filesize($path);
        if ($size === false || $size === 0) {
            throw new ValidationException('Media file is empty or unreadable: ' . $path);
        }
    }

    public function toPath(): string
    {
        return $this->path;
    }

    public function size(): int
    {
        $size = filesize($this->path);
        if ($size === false) {
            throw new ValidationException('Unable to determine media file size: ' . $this->path);
        }

        return (int) $size;
    }

    public function mimeType(): ?string
    {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo === false) {
            return null;
        }
        $mime = finfo_file($finfo, $this->path) ?: null;
        finfo_close($finfo);

        return $mime !== '' ? $mime : null;
    }
}
