# Gowa PHP SDK — Development Plan

> Root namespace: `BlacklineCloud\SDK\GowaPHP`
> Upstream reference: https://github.com/aldinokemal/go-whatsapp-web-multidevice (openapi 6.12.0, webhook-payload.md)

## 0. Discovery (must be completed before coding)
- [x] Re-read `.docs/official/readme.md` and note runtime modes (REST/MCP), auth (Basic), media constraints
- [x] Extract endpoint list & grouping from `.docs/official/openapi.yaml` (app, user, send, message, chat, group, newsletter)
- [x] Catalogue webhook event schemas from `.docs/official/webhook-payload.md` (message/receipt/group/media/special flags)
- [x] Capture upstream versions & checksums (openapi.yaml + webhook-payload.md) into `upstream/manifest.json`

## 1. Repository Scaffolding & Quality Gates
- [x] `composer.json`: PHP >= 8.2, PSR-4 autoload `BlacklineCloud\\SDK\\GowaPHP\\`
- [x] Require: `psr/http-client`, `psr/http-factory`, `psr/log`; dev: `phpstan/phpstan`, `vimeo/psalm`, `phpunit/phpunit` (or `pestphp/pest`), `friendsofphp/php-cs-fixer`
- [x] Tooling configs: `.php-cs-fixer.php`, `phpstan.neon`, `psalm.xml`, `.editorconfig`
- [x] CI (GitHub Actions): lint, static analysis, tests, coverage upload; matrix PHP 8.2/8.3/8.4
- [x] Repo meta: `LICENSE` (MIT), `CODE_OF_CONDUCT.md`, `CONTRIBUTING.md`, `SECURITY.md`, `CHANGELOG.md`
- [x] GitHub templates: `.github/ISSUE_TEMPLATE/bug.yml`, `feature.yml`, `PULL_REQUEST_TEMPLATE.md`
- [x] Release automation: release-drafter or semantic-release workflow; Packagist auto-update hook

## 2. Core Architecture & Abstractions
- [x] Define `Contracts\Http\HttpTransportInterface` (PSR-7/18 based) and `Contracts\Http\MiddlewareInterface`
- [x] Implement default transport adapter (e.g., HTTPlug + discovery) with retry/backoff and idempotency token support
- [x] Introduce `ClientConfig` (immutable): baseUrl, auth, timeouts, serialization options, retry policy
- [x] Error model: rich domain exceptions hierarchy (transport, auth, validation, rate-limit, server)
- [x] Logging hooks (PSR-3) + correlation IDs

## 3. Domain Model & Serialization
- [x] Value Objects: `Jid`, `MessageId`, `Timestamp`, `PhoneNumber`, `MediaPath`, `WebhookSignature`
- [x] Enums: message ack status, receipt type, group action type, media mime types, presence states
- [ ] Request/Response DTOs per OpenAPI schemas; use readonly properties, typed collections (framework in place; DTOs for Login/Send/Devices/Privacy/UserInfo/Avatar/BusinessProfile/GroupInfoFromLink/GroupParticipants/ManageParticipant/Chat list & messages/Chat actions/Newsletter/Generic/Contacts/Group list/Participant requests/Message actions)
- [x] Serializer layer: JSON (fail-fast), request builders, response hydrators; map unknown fields conservatively
- [x] Validation: input guards (Design by Contract) with descriptive exceptions
- [ ] Self-review checklist applied to models & serializer (pending pass)

## 4. Service Clients (by API tag)
- [x] `AppClient`: login (QR/code), logout, reconnect, devices
- [x] `UserClient`: info, avatar, pushname, privacy, my groups/contacts/newsletters, business profile
- [ ] `SendClient`: text, image, audio, file, sticker, video, contact, link, location, poll, presence/chat presence
- [x] `MessageClient`: revoke, delete, reaction, update, read, star/unstar
- [x] `ChatClient`: list chats, chat messages, labels, pin
- [x] `GroupClient`: info-from-link, create, list, participants add/remove/promote/demote/export, join via link, participant requests approve/reject, leave, photo, name, locked, announce, topic, invite link
- [x] `NewsletterClient`: list, unfollow (extension ready)
- [x] Consistent method signatures: strongly-typed inputs/outputs; participant lists now variadic
- [ ] Unit tests + self-review per client (partial: App/User/Send/Group covered)

## 5. Webhook Support
- [ ] `WebhookVerifier` implementing HMAC-SHA256 signature check, constant-time comparison
- [ ] Event deserializer mapping payloads to domain events (message, receipt, group, media, special flags)
- [ ] Idempotency support (event ID tracking) and duplicate handling guidance
- [ ] Middleware example for common frameworks (PSR-15 adapter + Laravel/Symfony bridges) with docs
- [ ] Self-review checklist for webhook layer

## 6. Configuration & Extensibility
- [ ] Config builder from env/array with validation; sensible defaults (timeouts, base path)
- [ ] Pluggable middlewares: auth (Basic), tracing, retry, circuit breaker placeholder
- [ ] File/media upload strategy abstraction to support local file path, stream, PSR-7 stream
- [ ] Clock & UUID providers injected for testability

## 7. Update Tracking & Upstream Sync
- [ ] Create `bin/sync-upstream` to fetch latest `openapi.yaml` and `webhook-payload.md`, store in `upstream/` with hashes
- [ ] Create `bin/diff-openapi` to compare stored spec with generated DTO map; exit non-zero on drift
- [ ] CI scheduled job (weekly) to run diff and open issue/PR when changes detected
- [ ] Document upgrade workflow in `CONTRIBUTING.md` (regenerate DTOs, update changelog, bump version)

## 8. Testing Strategy
- [ ] Unit tests: value objects, serializers, signature verification, error mapping, retry policy
- [ ] Contract tests: golden files for request/response serialization vs OpenAPI examples
- [ ] Integration tests: HTTP mock server (e.g., `php-http/mock-client`); ensure retry/idempotency behavior
- [ ] Static analysis gates: PHPStan lvl max, Psalm lvl 1; 100% type coverage goal
- [ ] Code coverage target >= 90%, track via CI badge

## 9. Documentation & DX
- [ ] README: installation, quick start, minimal send example, webhook verification snippet, changelog link
- [ ] API docs: generated from PHPDoc + examples; publish via GitHub Pages action
- [ ] Usage recipes: login flows (QR/code), sending media, group admin, webhook handling, error handling patterns
- [ ] Contribution guide: coding standards, commit convention (Conventional Commits), review checklist, test matrix
- [ ] Example apps: CLI demo and minimal webhook consumer

## 10. Release & Maintenance
- [ ] Semantic versioning policy; document supported upstream API versions
- [ ] Release workflow: tag -> changelog -> GitHub release -> Packagist sync
- [ ] Deprecation policy and compatibility notes (breaking change window)
- [ ] Support matrix: PHP versions, HTTP clients
- [ ] Ongoing self-review: ensure SOLID, DRY, KISS, YAGNI, PSR compliance before each release

## Definition of Done
- [ ] All plan tasks completed
- [ ] Self-review checklist satisfied for every component
- [ ] CI green (lint, static analysis, tests, coverage)
- [ ] Docs updated (README + API + changelog)
