<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support;

use BlacklineCloud\SDK\GowaPHP\Domain\Value\Jid;
use BlacklineCloud\SDK\GowaPHP\Domain\Value\PhoneNumber;

final class InputValidator
{
    public static function jid(string $jid): string
    {
        return (new Jid($jid))->value();
    }

    public static function phone(string $phone): string
    {
        return (new PhoneNumber($phone))->value();
    }
}
