# WooCommerce SK/CZ Functions

This repository is the project root for the WooCommerce SK/CZ Functions product line.

Public GitHub repository:

- https://github.com/Ljuk67/Woo-sk-cz-functions

Today it contains the free plugin:

- `woocommerce-sk-cz-functions/`

It is also structured to later contain the premium plugin:

- `woocommerce-sk-cz-functions-premium/`

This repo is not a WordPress installation root. It is the project workspace that holds plugin code, shared docs, and local-only notes.

## What This Project Is

WooCommerce SK/CZ Functions is a WordPress/WooCommerce plugin project focused on practical store features commonly needed in Slovak and Czech ecommerce sites.

The free plugin currently targets features such as:

- company checkout fields
- configurable checkout order button text with Slovak and Czech defaults
- GDPR consent handling
- fixed Cash on Delivery fee for the WooCommerce `cod` gateway
- catalog and checkout UX improvements
- translation-ready SK/CZ store behavior

The checkout button text setting appears in `WooCommerce > Settings > WooCommerce SK/CZ` above the feature checkboxes. If the merchant leaves the predefined text unchanged or clears the field, the plugin uses the translated default for the active site language: Slovak `Objednať s povinnosťou platby` or Czech `Objednat s povinností platby`. Saving different text stores that merchant-defined wording for both classic checkout and Checkout Block.

## Repository Layout

```text
/
  AGENTS.md
  README.md
  docs/
    roadmap.md
  memory/
    current-state.md
  .local/                         # ignored; private machine-specific notes
  woocommerce-sk-cz-functions/    # free plugin root
  woocommerce-sk-cz-functions-premium/  # planned premium plugin root
```

## Source Of Truth

Use these files for shared project context:

- `AGENTS.md`: stable repo instructions for AI/code agents
- `docs/roadmap.md`: product direction, phases, backlog, strategic notes
- `memory/current-state.md`: live project state, active tasks, recent completions
- `woocommerce-sk-cz-functions/readme.txt`: WordPress.org plugin readme for the free plugin package

## WordPress.org Release Notes

The free plugin is prepared for WordPress.org distribution from the `woocommerce-sk-cz-functions/` directory.

Important rules for public release:

- Keep the main plugin header version and `readme.txt` Stable tag in sync.
- Keep `readme.txt` valid against the official WordPress.org readme validator before submission.
- Do not bundle generated ZIP files in Git.
- Do not add external update-checker code to the WordPress.org build; WordPress.org must serve updates for the directory version.
- Keep all scripts and styles local to the plugin unless a documented WordPress/WooCommerce service requires otherwise.
- Keep premium/add-on code outside the free plugin package.

## Local And Private Files

Put private repo-specific material in `.local/`.

Examples:

- machine-specific setup notes
- LocalWP paths and symlink targets
- temporary task lists
- scratch notes
- private release checklists
- one-off experiment output

If a note becomes important for the project as a whole, promote it from `.local/` into `docs/` or `memory/`.

This keeps `.gitignore` small and avoids scattering ignored files across the repo.

## Development Conventions

- Keep free and premium codebases separate from the start.
- Put shared project documentation in committed files, not in ignored local notes.
- Keep `.local/` as the default home for private or temporary material.
- Add repo-wide tooling at the repo root if needed later.
- Keep plugin-specific packaging files inside each plugin root.

## Local Plugin Usage

The free plugin root is:

- `woocommerce-sk-cz-functions/`

Typical local usage is to symlink or copy that directory into a WordPress site's `wp-content/plugins/` directory, then activate it with WooCommerce enabled.

Any machine-specific commands, paths, or symlink notes should live in `.local/`, not in committed docs.

## Recommended Next Repo-Level Improvements

- add `.editorconfig`
- add PHP linting/coding-standard tooling when you are ready to enforce it
- add a shared local setup doc if the WordPress test environment becomes repeatable enough to document
- add a premium plugin root when premium work starts instead of mixing premium code into the free plugin
