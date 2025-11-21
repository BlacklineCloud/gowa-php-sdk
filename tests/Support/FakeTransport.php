<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Support;

use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FakeTransport implements HttpTransportInterface
{
    public ?RequestInterface $lastRequest = null;

    public function __construct(private readonly ResponseInterface $response)
    {
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->lastRequest = $request;
        return $this->response;
    }
}
