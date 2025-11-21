<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
