<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Exception;

class RateLimitException extends SdkException
{
    public function __construct(string $message = 'Rate limited', public readonly ?int $retryAfterSeconds = null)
    {
        parent::__construct($message);
    }
}
