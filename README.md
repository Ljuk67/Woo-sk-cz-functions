# Nimble Woo SK/CZ Functions

This repository is the project root for the Nimble Woo SK/CZ Functions product line.

Public GitHub repository:

- https://github.com/Ljuk67/Woo-sk-cz-functions

This repo is not a WordPress installation root. It is the project workspace that holds plugin code, shared docs, and release-related files.

## What This Project Is

Nimble Woo SK/CZ Functions is a WordPress/WooCommerce plugin project focused on practical store features commonly needed in Slovak and Czech ecommerce sites.

The free plugin currently includes these functions:

- company checkout toggle with company name, ICO, DIC, and IC DPH fields
- configurable checkout order button text with Slovak and Czech translated defaults
- GDPR consent checkbox for classic checkout, with privacy-policy link support
- fixed Cash on Delivery fee for the WooCommerce `cod` payment gateway
- hide paid shipping methods when free shipping is available
- hide the Additional Information product tab on single product pages
- optional move of Additional Information tab content into the long product description
- product subcategories row above products on category archive pages

Checkout-facing functions are implemented for both classic checkout and Checkout Block where the current free plugin supports that surface. The GDPR checkbox is intentionally limited to classic checkout because WooCommerce handles legal consent differently in Checkout Block.

The checkout button text setting appears in `WooCommerce > Settings > Nimble Woo SK/CZ` above the feature checkboxes. If the merchant leaves the predefined text unchanged or clears the field, the plugin uses the translated default for the active site language: Slovak `Objednať s povinnosťou platby` or Czech `Objednat s povinností platby`. Saving different text stores that merchant-defined wording for both classic checkout and Checkout Block.

## Repository Layout

```text
/ 
  AGENTS.md
  README.md
  docs/
  memory/
  .local/                        # ignored; private machine-specific notes
  nimble-woo-sk-cz-functions/    # free plugin root
  nimble-woo-sk-cz-functions-premium/  # planned premium plugin root
```

## Source Of Truth

Use these files for shared project context:

- `AGENTS.md`: stable repo instructions for agents
- `docs/roadmap.md`: product direction and release phases
- `memory/current-state.md`: live project state and active work
- `nimble-woo-sk-cz-functions/readme.txt`: WordPress.org plugin readme for the free plugin package
- `nimble-woo-sk-cz-functions/`: free plugin package source

## WordPress.org Release Notes

The free plugin is prepared for WordPress.org distribution from the `nimble-woo-sk-cz-functions/` directory.

Important rules for public release:

- Keep the main plugin header version and `readme.txt` Stable tag in sync.
- Keep `readme.txt` valid against the official WordPress.org readme validator before submission.
- Do not bundle generated ZIP files in Git.
- Do not add external update-checker code to the WordPress.org build; WordPress.org must serve updates for the directory version.
- Keep all scripts and styles local to the plugin unless a documented WordPress/WooCommerce service requires otherwise.
- Keep premium/add-on code outside the free plugin package.

## Development Conventions

- Keep free and premium codebases separate from the start.
- Add repo-wide tooling at the repo root if needed later.
- Keep plugin-specific packaging files inside each plugin root.

## Local Plugin Usage

The free plugin root is:

- `nimble-woo-sk-cz-functions/`

Typical local usage is to symlink or copy that directory into a WordPress site's `wp-content/plugins/` directory, then activate it with WooCommerce enabled.
