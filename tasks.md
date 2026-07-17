# Tasks: GitHub-to-WordPress.org SVN Deploy Workflow

Goal: publish `nimble-help-sk-cz-store-tools` to WordPress.org SVN from GitHub tags using `10up/action-wordpress-plugin-deploy`, so local work stays Git-only and SVN becomes automated release transport.

## 1. Repository prep

- [ ] Keep GitHub as source of truth for plugin development.
- [ ] Keep plugin source in `nimble-help-sk-cz-store-tools/`.
- [ ] Keep WordPress.org slug fixed as `nimble-help-sk-cz-store-tools`.
- [ ] Confirm version fields match before each release:
  - [ ] `nimble-help-sk-cz-store-tools/nimble-help-sk-cz-store-tools.php` header `Version`
  - [ ] `WSCF_VERSION`
  - [ ] `nimble-help-sk-cz-store-tools/readme.txt` `Stable tag`
  - [ ] `readme.txt` changelog section

## 2. WordPress.org assets layout

- [ ] Create repo-root `.wordpress-org/` directory.
- [ ] Move WordPress.org directory screenshots there:
  - [ ] `.wordpress-org/screenshot-1.jpeg`
  - [ ] `.wordpress-org/screenshot-2.jpeg`
  - [ ] `.wordpress-org/screenshot-3.jpeg`
  - [ ] `.wordpress-org/screenshot-4.jpeg`
- [ ] Keep runtime plugin images inside plugin source:
  - [ ] `nimble-help-sk-cz-store-tools/assets/img/nimble-logo.jpg`
  - [ ] `nimble-help-sk-cz-store-tools/assets/img/child_cat.jpeg`
- [ ] Do not ship screenshot files inside plugin ZIP unless plugin runtime uses them.
- [ ] Optional later: add WordPress.org icon/banner files to `.wordpress-org/`.

## 3. GitHub secrets

- [ ] In GitHub repo settings, add Actions secrets:
  - [ ] `SVN_USERNAME`
  - [ ] `SVN_PASSWORD`
- [ ] Use WordPress.org SVN-specific password if configured.
- [ ] Confirm username capitalization exactly matches WordPress.org profile.

## 4. GitHub Actions workflow

- [ ] Add `.github/workflows/deploy-wordpress.yml`.
- [ ] Trigger workflow on version tags:
  - [ ] `*.*.*`
- [ ] Add manual `workflow_dispatch` with `dry_run` input.
- [ ] Checkout code with `actions/checkout@v4`.
- [ ] Build clean deploy directory from plugin subfolder:
  - [ ] copy `nimble-help-sk-cz-store-tools/` into `build/`
  - [ ] exclude `assets/img/screenshot-*.jpeg`
- [ ] Deploy with `10up/action-wordpress-plugin-deploy@stable`.
- [ ] Set action env:
  - [ ] `SLUG: nimble-help-sk-cz-store-tools`
  - [ ] `BUILD_DIR: build`
  - [ ] `SVN_USERNAME: ${{ secrets.SVN_USERNAME }}`
  - [ ] `SVN_PASSWORD: ${{ secrets.SVN_PASSWORD }}`
- [ ] Use `dry-run: true` for first manual run.

Suggested workflow draft:

```yaml
name: Deploy to WordPress.org

on:
  push:
    tags:
      - '*.*.*'
  workflow_dispatch:
    inputs:
      dry_run:
        description: 'Dry run only'
        required: true
        default: 'true'
        type: choice
        options:
          - 'true'
          - 'false'

jobs:
  deploy:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v4

      - name: Build plugin directory
        run: |
          mkdir -p build
          rsync -a --delete \
            --exclude='assets/img/screenshot-*.jpeg' \
            nimble-help-sk-cz-store-tools/ \
            build/

      - name: Deploy to WordPress.org
        uses: 10up/action-wordpress-plugin-deploy@stable
        env:
          SVN_USERNAME: ${{ secrets.SVN_USERNAME }}
          SVN_PASSWORD: ${{ secrets.SVN_PASSWORD }}
          SLUG: nimble-help-sk-cz-store-tools
          BUILD_DIR: build
        with:
          dry-run: ${{ github.event_name == 'workflow_dispatch' && github.event.inputs.dry_run == 'true' }}
```

## 5. Release checklist

- [ ] Finish code changes locally.
- [ ] Run preflight checks:
  - [ ] `git status --short`
  - [ ] `rg -n "<script|<style|wc_enqueue_js" nimble-help-sk-cz-store-tools`
  - [ ] `find nimble-help-sk-cz-store-tools -name '*.php' -exec php -l {} \;`
  - [ ] `git diff --check`
  - [ ] WordPress.org readme validator
- [ ] Commit to Git.
- [ ] Push to GitHub.
- [ ] Run workflow manually with `dry_run=true`.
- [ ] Review GitHub Actions log.
- [ ] Create and push release tag:
  - [ ] `git tag 1.0.0`
  - [ ] `git push origin 1.0.0`
- [ ] Confirm GitHub Action deploy succeeds.
- [ ] Confirm public plugin page updates:
  - [ ] https://wordpress.org/plugins/nimble-help-sk-cz-store-tools
- [ ] Install from WordPress.org on clean/staging WordPress site.
- [ ] Test activation with WooCommerce active.
- [ ] Test settings page and core checkout/catalog functions.

## 6. Rules for future releases

- [ ] Never edit WordPress.org SVN directly unless emergency recovery is needed.
- [ ] Never upload generated ZIP files to SVN.
- [ ] Release only from Git tags.
- [ ] Tag name must match plugin version and `Stable tag`.
- [ ] Use GitHub PR/commit history for development review.
- [ ] Use WordPress.org SVN only as release destination.
