# Releasing

Checklist for publishing a new version to GitHub Releases and Packagist.

## Prerequisites
- MIT license confirmed in `LICENSE` and `composer.json`.
- `CHANGELOG.md` updated with the new version and date.
- README and docs reflect any new behavior.
- Tests and quality gates pass locally: `composer lint && composer stan && composer psalm && composer test`.

## Cut a release
1. Choose the version (SemVer). For the first release: `v1.0.0`.
2. Commit all changes and ensure `git status` is clean.
3. Tag and push:
   ```bash
   git tag -a v1.0.0 -m "v1.0.0"
   git push origin v1.0.0
   ```
4. GitHub Actions will:
   - Run lint, static analysis, and tests with coverage.
   - Create a GitHub Release with generated notes.
   - Notify Packagist if credentials are present.

## After publishing
- Verify the GitHub Release appears and assets/notes look correct.
- Confirm the new version shows on Packagist (`https://packagist.org/packages/blacklinecloud/gowa-php-sdk`).
- Update `CHANGELOG.md` by adding a fresh `[Unreleased]` section for future work.
