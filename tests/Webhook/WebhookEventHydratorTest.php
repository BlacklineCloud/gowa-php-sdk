<?php

declare(strict_types=1);

namespace BlacklineCloud\SDK\GowaPHP\Tests\Webhook;

use BlacklineCloud\SDK\GowaPHP\Exception\ValidationException;
use BlacklineCloud\SDK\GowaPHP\Webhook\WebhookEventHydrator;
use PHPUnit\Framework\TestCase;

final class WebhookEventHydratorTest extends TestCase
{
    public function testHydratesTextMessage(): void
    {
        $payload = [
            'sender_id' => '123',
            'chat_id'   => '123',
            'from'      => '123@s.whatsapp.net',
            'timestamp' => '2024-01-15T10:30:00Z',
            'pushname'  => 'Alice',
            'message'   => [
                'text'           => 'Hello',
                'id'             => 'mid',
                'replied_id'     => null,
                'quoted_message' => null,
            ],
        ];

        $event = (new WebhookEventHydrator())->hydrate($payload);

        self::assertSame('message', $event->type);
        self::assertSame('Hello', $event->message?->text);
    }

    public function testHydratesReceipt(): void
    {
        $payload = [
            'sender_id' => 's',
            'chat_id'   => 'c',
            'from'      => 'f',
            'timestamp' => '2024-01-15T10:30:00Z',
            'event'     => 'message.ack',
            'payload'   => [
                'chat_id'                  => 'g',
                'from'                     => 'f in g',
                'ids'                      => ['id1'],
                'receipt_type'             => 'delivered',
                'receipt_type_description' => 'delivered desc',
                'sender_id'                => 'sid',
            ],
        ];

        $event = (new WebhookEventHydrator())->hydrate($payload);

        self::assertSame('receipt', $event->type);
        self::assertSame(['id1'], $event->receipt?->ids);
    }

    public function testHydratesGroupParticipants(): void
    {
        $payload = [
            'sender_id' => 's',
            'chat_id'   => 'c',
            'from'      => 'f',
            'timestamp' => '2024-01-15T10:30:00Z',
            'event'     => 'group.participants',
            'payload'   => [
                'chat_id' => 'g',
                'type'    => 'join',
                'jids'    => ['a@s.whatsapp.net'],
            ],
        ];

        $event = (new WebhookEventHydrator())->hydrate($payload);

        self::assertSame('group.participants', $event->type);
        self::assertSame('join', $event->groupParticipants?->type);
    }

    public function testHydratesLocationWithNumericCoordinates(): void
    {
        $payload = [
            'sender_id' => '123',
            'chat_id'   => '123',
            'from'      => '123@s.whatsapp.net',
            'timestamp' => '2024-01-15T10:30:00Z',
            'message'   => [
                'text'           => '',
                'id'             => 'mid',
                'replied_id'     => null,
                'quoted_message' => null,
            ],
            'location' => [
                'degreesLatitude'  => 1.23,
                'degreesLongitude' => '45.67',
                'name'             => null,
                'address'          => null,
            ],
        ];

        $event = (new WebhookEventHydrator())->hydrate($payload);

        self::assertNotNull($event->location);
        self::assertSame(1.23, $event->location->latitude);
        self::assertSame(45.67, $event->location->longitude);
    }

    public function testRejectsInvalidTimestamp(): void
    {
        $payload = [
            'sender_id' => 's',
            'chat_id'   => 'c',
            'from'      => 'f',
            'timestamp' => 'not-a-date',
            'message'   => [
                'text'           => '',
                'id'             => 'mid',
                'replied_id'     => null,
                'quoted_message' => null,
            ],
        ];

        $this->expectException(ValidationException::class);
        (new WebhookEventHydrator())->hydrate($payload);
    }
}
