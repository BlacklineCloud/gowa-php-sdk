<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Support;

use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class FakeTransport implements HttpTransportInterface
{
    public ?RequestInterface $lastRequest = null;

    /** @var list<ResponseInterface> */
    private array $responses;

    public function __construct(ResponseInterface|array $response)
    {
        $this->responses = \is_array($response) ? array_values($response) : [$response];
    }

    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->lastRequest = $request;

        if ($this->responses === []) {
            throw new \RuntimeException('No fake response available');
        }

        $response = array_shift($this->responses);
        $this->responses[] = $response; // rotate to allow re-use if calls exceed provided list

        return $response;
    }
}
