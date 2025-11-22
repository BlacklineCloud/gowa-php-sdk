# Production Hardening Plan (Gowa PHP SDK)

## Findings Snapshot
- Webhook hydration fails when `location` latitude/longitude are numeric (spec uses numbers) because we require strings then cast.
- API surface incomplete vs OpenAPI 6.12.0: missing `/user/pushname`, `/group/info`, `/group/participants/export` DTOs/clients/tests.
- Media upload abstraction promised in plan is absent; `src/Support/Media` is empty and send endpoints only take file paths.
- Changelog/DoD misaligned with actual work; plan claims complete while known gaps exist.
- Upstream drift workflow only fails the job; it does not open issues/PRs or refresh artifacts.
- Dev DX: unused dev dependency (`nikic/php-parser`) and verbose manual wiring in README.

## Refactoring & Release-Readiness Tasks
- [ ] Webhook location robustness
  - [x] Add float-friendly accessors in `ArrayReader` (e.g., `requireFloat`/`optionalFloat`).
  - [x] Update `WebhookEventHydrator` to consume numeric lat/long and validate timestamps via `Timestamp` value object.
  - [x] Extend webhook tests to cover numeric coordinates, missing optional fields, and invalid signatures.
- [ ] Close OpenAPI coverage gaps
  - [x] Implement DTOs/hydrators/client methods/tests for `/user/pushname`.
  - [x] Implement `GroupInfo` DTO/hydrator/client method/tests for `/group/info`.
  - [x] Add participant export support: request builder, CSV response handling (stream/string), and tests for `/group/participants/export`.
  - [x] Align fixtures/golden contract tests with new endpoints.
- [ ] Media upload strategy
  - [x] Introduce `MediaUploadInterface` (file path, PSR-7 stream, resource) and a default adapter.
  - [x] Wire send media endpoints to accept the abstraction (preserve BC by accepting string paths).
  - [x] Add validation for file existence/size and MIME hints; cover with unit tests.
- [ ] Documentation & changelog correctness
  - [ ] Update `CHANGELOG.md` to reflect current feature set and pending work.
  - [ ] Reconcile `docs/plan/sdk-plan.md` DoD/checkboxes with actual status or add a note linking to this hardening plan.
  - [ ] Trim unused dev dependency (`nikic/php-parser`) or document its purpose.
  - [ ] Add a client factory example to README to reduce manual wiring.
- [ ] Upstream drift automation
  - [ ] Enhance `bin/diff-openapi` + scheduled workflow to comment/open issue/PR on drift (using `peter-evans/create-issue-from-file` or similar).
  - [ ] Document the drift response procedure in `CONTRIBUTING.md`.
- [ ] Verification & release gating
  - [ ] Run `composer lint && composer stan && composer psalm && composer test` with coverage; ensure CI badge reflects status.
  - [ ] Bump version + tag only after above tasks are green; update Packagist hook notes.
