<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

final readonly class Pagination
{
    public function __construct(
        public int $limit,
        public int $offset,
        public int $total,
    ) {
    }
}
