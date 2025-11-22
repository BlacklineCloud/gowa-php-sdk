<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Support;

final class NoopCircuitBreaker implements CircuitBreakerInterface
{
    public function call(string $key, callable $action): mixed
    {
        return $action();
    }
}
