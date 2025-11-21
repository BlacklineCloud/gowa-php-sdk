<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Serialization;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;

final class Json
{
    /** @return array<string,mixed>|list<mixed> */
    public static function decode(string $payload): array
    {
        $data = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);

        if (!\is_array($data)) {
            throw new ValidationException('JSON payload must decode to array');
        }

        return $data;
    }

    /**
     * @param array<string,mixed>|list<mixed> $data
     */
    public static function encode(array $data): string
    {
        return json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }
}
