<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Domain\Dto;

/** @psalm-immutable */
final readonly class ChatList
{
    /** @param list<Chat> $data */
    public function __construct(
        public array $data,
        public Pagination $pagination,
    ) {
    }
}
