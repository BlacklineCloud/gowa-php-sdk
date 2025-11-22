<?php

declare(strict_types=1);

use BlacklineCloud\SDK\GowaPHP\Client\SendClient;
use BlacklineCloud\SDK\GowaPHP\Config\ClientConfigBuilder;
use BlacklineCloud\SDK\GowaPHP\Http\Psr18Transport;
use BlacklineCloud\SDK\GowaPHP\Http\Middleware\{AuthMiddleware,CorrelationIdMiddleware,IdempotencyMiddleware,LoggingMiddleware,RetryMiddleware};
use BlacklineCloud\SDK\GowaPHP\Serialization\Hydrator\SendResponseHydrator;
use BlacklineCloud\SDK\GowaPHP\Support\{NativeUuidGenerator,SystemClock};
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7\HttpClient\CurlClient;
use Psr\Log\NullLogger;

require __DIR__ . '/../vendor/autoload.php';

$config = ClientConfigBuilder::fromEnv();

$psr17 = new Psr17Factory();
$uuid = new NativeUuidGenerator();
$transport = new Psr18Transport(
    new CurlClient($psr17),
    $config,
    new NullLogger(),
    new AuthMiddleware($config),
    new CorrelationIdMiddleware($uuid),
    new IdempotencyMiddleware($uuid),
    new LoggingMiddleware(new NullLogger()),
    new RetryMiddleware($config),
);

$send = new SendClient($config, $transport, $psr17, $psr17, new SendResponseHydrator());

$to = $argv[1] ?? null;
$text = $argv[2] ?? null;
if ($to === null || $text === null) {
    fwrite(STDERR, "Usage: php examples/cli-send.php <jid> <text>\n");
    exit(1);
}

$response = $send->text($to, $text);

printf("Message %s: %s\n", $response->messageId, $response->status);
