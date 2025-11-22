<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Exception;

final class ServerException extends SdkException
{
    public function __construct(string $message = 'Server error', public readonly ?int $statusCode = null)
    {
        parent::__construct($message);
    }
}
