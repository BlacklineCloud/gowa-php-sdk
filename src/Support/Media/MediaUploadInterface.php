<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support\Media;

/**
 * Represents a media source that can be materialized to a filesystem path for upload.
 */
interface MediaUploadInterface
{
    /**
     * Return a filesystem path that the API server can access.
     * Implementations may create temporary files; callers should treat the returned path as ephemeral.
     */
    public function toPath(): string;
}
