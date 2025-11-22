# Usage Recipes

## Login with Pairing Code
```php
$app = /* AppClient */;
$response = $app->loginWithCode('628123456789');
echo $response->pairCode; // e.g., ABCD-1234
```

## Send Media
```php
$send = /* SendClient */;
$send->image('jid@s.whatsapp.net', '/path/to/image.jpg', caption: 'hi');
$send->video('jid@s.whatsapp.net', '/path/to/video.mp4', caption: 'watch', compress: true);
$send->sticker('jid@s.whatsapp.net', '/path/to/image.png');
```

## Group Admin
```php
$group = /* GroupClient */;
// create
$created = $group->create('Project Team', 'user1@s.whatsapp.net', 'user2@s.whatsapp.net');
// add participants
$group->addParticipants($created->groupId, 'user3@s.whatsapp.net');
// change settings
$group->setAnnounce($created->groupId, true);
$group->setTopic($created->groupId, 'Rules');
```

## Webhook Handling
See `docs/notes/webhook-verification.md` for verification + hydrator.

## Error Handling
- Transport/validation/auth errors throw typed exceptions under `BlacklineCloud\SDK\GowaPHP\Exception`.
- Wrap calls in try/catch and inspect exception type/message.
