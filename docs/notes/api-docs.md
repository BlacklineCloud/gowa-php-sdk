# API Docs Plan

- Generate static docs from PHPDoc using Doctum or Sami-equivalent (CI job to build and publish to `gh-pages`).
- Include sections for Clients (App/User/Send/Message/Chat/Group/Newsletter), DTOs, Webhook models, and config.
- Example command (to be wired in CI):
  ```bash
  vendor/bin/doctum.php update doctum.php
  ```
- Publish via GitHub Pages action (workflow TODO): build docs and push to `gh-pages` branch on tags.
