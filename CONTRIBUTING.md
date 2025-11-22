# Contributing

Thanks for your interest in contributing to the Gowa PHP SDK!

## Ground Rules
- Follow SOLID, DRY, KISS, YAGNI, PSR standards.
- Depend on abstractions (DIP) and inject dependencies via constructors.
- Fail fast with descriptive domain errors.
- Keep public APIs type-safe; avoid associative arrays in signatures.
- Run tooling (`composer lint`, `composer stan`, `composer psalm`, `composer test`) before opening a PR.
- Use Conventional Commits for all commits (e.g., `feat: add webhook verifier`).

## Development Workflow
1. Complete the Discovery checklist in `docs/plan/sdk-plan.md` before coding a new area.
2. Work in small increments; keep classes single-responsibility with no duplication.
3. Add or update tests alongside code changes.
4. Update documentation (README, CHANGELOG) when behavior changes.
5. Ensure CI passes for PHP 8.2/8.3/8.4.

## Upstream drift response
- Scheduled CI runs `bin/diff-openapi` weekly to detect spec drift (OpenAPI/webhook docs).
- If drift is detected (issue auto-created), remediate by:
  1. Running `bin/sync-upstream` to refresh `.docs/official/*`.
  2. Regenerating/adjusting DTOs, hydrators, and clients to match spec changes.
  3. Updating `upstream/manifest.json` and re-running `bin/diff-openapi` (should exit 0).
  4. Adding changelog entries and version bumping as needed.
  5. Opening a PR referencing the drift issue.

## Self-Review Checklist (run after each component)
- [ ] Is this code self-documenting?
- [ ] Have I eliminated all duplication?
- [ ] Does each class have a single responsibility?
- [ ] Are all dependencies injected via constructor?
- [ ] Have I used appropriate design patterns?
- [ ] Is this the simplest solution that works (KISS)?
- [ ] Is this necessary (YAGNI)?
- [ ] Is the code PSR-compliant?

## Reporting Security Issues
Please email security@blackline.cloud and do not open a public issue.

## Release Process
- We use semantic versioning.
- Draft releases via Release Drafter; tags trigger packaging and Packagist updates.
- Breaking changes require a major version bump and changelog entry.

## Upgrade Workflow
- Run `bin/diff-openapi` to detect upstream spec drift.
- If drift is reported, run `bin/sync-upstream` (requires network) to refresh `.docs/official/*` and update `upstream/manifest.json`.
- Regenerate or adjust DTOs/hydrators if needed, update plan checkboxes, and bump CHANGELOG.
- Open a PR with the updated manifest and regenerated code.

## Test Matrix
- Run `composer lint && composer stan && composer psalm && composer test` before PRs.
- Contract tests live in `tests/Contract` (golden fixtures) and should be updated alongside API changes.
- Integration tests (TODO) will use mock HTTP to validate retry/idempotency behavior.

## Self-Review for Clients & Webhooks
- Ensure no public arrays in method signatures; prefer typed DTOs/value objects and variadics.
- Confirm webhook signatures are validated before deserialization.
- Keep hydrators fail-fast with clear validation messages.
