<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Client;

use BlacklineCloud\SDK\GowaPHP\Config\ClientConfig;
use BlacklineCloud\SDK\GowaPHP\Contracts\Http\HttpTransportInterface;
use BlacklineCloud\SDK\GowaPHP\Domain\Dto\SendResponse;
use BlacklineCloud\SDK\GowaPHP\Domain\Enum\PresenceState;
use BlacklineCloud\SDK\GowaPHP\Http\ApiClient;
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SendResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Support\InputValidator;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;

final class SendClient extends ApiClient
{
    public function __construct(
        ClientConfig $config,
        HttpTransportInterface $transport,
        RequestFactoryInterface $requestFactory,
        StreamFactoryInterface $streamFactory,
        private readonly SendResponseHydrator $sendHydrator,
    ) {
        parent::__construct($config, $transport, $requestFactory, $streamFactory);
    }

    public function text(string $to, string $message, ?string $replyMessageId = null): SendResponse
    {
        $body = [
            'jid'  => InputValidator::jid($to),
            'text' => $message,
        ];
        if ($replyMessageId !== null) {
            $body['reply_message_id'] = $replyMessageId;
        }

        return $this->hydrate($this->post('/send/message', $body));
    }

    public function link(string $to, string $link, ?string $caption = null): SendResponse
    {
        $body = [
            'jid'  => InputValidator::jid($to),
            'link' => $link,
        ];
        if ($caption !== null) {
            $body['caption'] = $caption;
        }

        return $this->hydrate($this->post('/send/link', $body));
    }

    public function location(string $to, float $lat, float $lng, ?string $name = null, ?string $address = null): SendResponse
    {
        $body = [
            'jid'       => InputValidator::jid($to),
            'latitude'  => $lat,
            'longitude' => $lng,
        ];
        if ($name !== null) {
            $body['name'] = $name;
        }
        if ($address !== null) {
            $body['address'] = $address;
        }

        return $this->hydrate($this->post('/send/location', $body));
    }

    public function contact(string $to, string $displayName, string $phone): SendResponse
    {
        return $this->hydrate($this->post('/send/contact', [
            'jid'          => InputValidator::jid($to),
            'display_name' => $displayName,
            'phone_number' => InputValidator::phone($phone),
        ]));
    }

    public function presence(string $to, string $presence): SendResponse
    {
        return $this->hydrate($this->post('/send/presence', [
            'jid'      => InputValidator::jid($to),
            'presence' => $presence,
        ]));
    }

    public function chatPresence(string $to, PresenceState $state): SendResponse
    {
        return $this->hydrate($this->post('/send/chat-presence', [
            'jid'      => InputValidator::jid($to),
            'presence' => $state->value,
        ]));
    }

    public function image(string $to, string $path, ?string $caption = null, bool $compress = true): SendResponse
    {
        return $this->hydrate($this->post('/send/image', [
            'jid'      => InputValidator::jid($to),
            'path'     => $path,
            'caption'  => $caption,
            'compress' => $compress,
        ]));
    }

    public function audio(string $to, string $path, ?string $caption = null): SendResponse
    {
        return $this->hydrate($this->post('/send/audio', [
            'jid'     => InputValidator::jid($to),
            'path'    => $path,
            'caption' => $caption,
        ]));
    }

    public function file(string $to, string $path, ?string $caption = null): SendResponse
    {
        return $this->hydrate($this->post('/send/file', [
            'jid'     => InputValidator::jid($to),
            'path'    => $path,
            'caption' => $caption,
        ]));
    }

    public function sticker(string $to, string $path, ?string $caption = null): SendResponse
    {
        return $this->hydrate($this->post('/send/sticker', [
            'jid'     => InputValidator::jid($to),
            'path'    => $path,
            'caption' => $caption,
        ]));
    }

    public function video(string $to, string $path, ?string $caption = null, bool $compress = true): SendResponse
    {
        return $this->hydrate($this->post('/send/video', [
            'jid'      => InputValidator::jid($to),
            'path'     => $path,
            'caption'  => $caption,
            'compress' => $compress,
        ]));
    }

    public function poll(string $to, string $question, string ...$options): SendResponse
    {
        return $this->hydrate($this->post('/send/poll', [
            'jid'      => InputValidator::jid($to),
            'question' => $question,
            'options'  => $options,
        ]));
    }

    /** @param array<string,mixed> $payload */
    private function hydrate(array $payload): SendResponse
    {
        return $this->sendHydrator->hydrate($payload);
    }
}
