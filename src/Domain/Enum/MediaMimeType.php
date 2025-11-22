<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Enum;

enum MediaMimeType: string
{
    case Jpeg = 'image/jpeg';
    case Png  = 'image/png';
    case Webp = 'image/webp';
    case Gif  = 'image/gif';
    case Mp4  = 'video/mp4';
    case Ogg  = 'audio/ogg';
    case Pdf  = 'application/pdf';
}
