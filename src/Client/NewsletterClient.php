<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Client;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\GenericResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\NewsletterResponse;
use BlacklineCloud\SDK\GowaPHP\Http\ApiClient;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\GenericResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\NewsletterResponseHydrator;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class NewsletterClient extends ApiClient
{
    public function __construct(
        ClientConfig $config,
        HttpTransportInterface $transport,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        private readonly NewsletterResponseHydrator $newsletterHydrator,
        private readonly GenericResponseHydrator $genericHydrator,
    ) {
        parent::__construct($config, $transport, $requestFactory, $streamFactory);
    }

    public function list(): NewsletterResponse
    {
        return $this->newsletterHydrator->hydrate($this->get('/user/my/newsletters'));
    }

    public function unfollow(string $newsletterId): GenericResponse
    {
        return $this->genericHydrator->hydrate($this->post('/newsletter/unfollow', [
            'newsletter_id' => $newsletterId,
        ]));
    }
}
