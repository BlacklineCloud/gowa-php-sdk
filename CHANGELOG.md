# Changelog

All notable changes to this project will be documented in this file.

## [Unreleased]
- Nothing yet.

## [1.0.0] - 2025-11-24
### Added
- PSR-18 transport pipeline with auth, correlation ID, idempotency, retry, and optional circuit breaker middleware.
- Immutable `ClientConfig` with env/array builder; UUID and clock abstractions.
- Typed DTOs/hydrators for app/user/send/message/chat/group/newsletter endpoints (OpenAPI 6.12.0 parity).
- Webhook support: signature verifier, event hydrator, value objects, and examples.
- Media upload strategy supporting file paths and PSR-7 streams; send media endpoints accept `MediaUploadInterface`.
- Upstream drift scripts (`bin/sync-upstream`, `bin/diff-openapi`) and GitHub Actions for CI, release, docs, and drift checks.
- Developer tooling: PHPStan/Psalm/PHPUnit configs, php-cs-fixer, coverage gate script, issue/PR templates, code of conduct, security policy.
- Examples and contract tests for send, pushname, group info, participant export, and webhook hydration.

### Changed
- `GenericResponse` now accepts array|string|null results with validation.
- API clients now reuse URI builder for CSV export; webhook hydration validates timestamps and numeric coordinates.

### Known gaps
- Coverage badge and docs site deployment remain placeholders.
- Drift workflow currently fails on spec drift but does not yet auto-open issues/PRs.
