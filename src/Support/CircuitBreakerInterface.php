<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support;

interface CircuitBreakerInterface
{
    /**
     * Execute an action under a breaker identified by key.
     */
    public function call(string $key, callable $action): mixed;
}
