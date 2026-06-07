# WooCommerce SK/CZ Functions

This repository is the project root for the WooCommerce SK/CZ Functions product line.

Public GitHub repository:

- https://github.com/Ljuk67/Woo-sk-cz-functions

Today it contains the free plugin:

- `woocommerce-sk-cz-functions/`

It is also structured to later contain the premium plugin:

- `woocommerce-sk-cz-functions-premium/`

This repo is not a WordPress installation root. It is the project workspace that holds plugin code and release files.

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
  README.md
  woocommerce-sk-cz-functions/    # free plugin root
  woocommerce-sk-cz-functions-premium/  # planned premium plugin root
```

## Source Of Truth

Use these files for shared project context:

- `woocommerce-sk-cz-functions/readme.txt`: WordPress.org plugin readme for the free plugin package
- `woocommerce-sk-cz-functions/`: free plugin package source

## WordPress.org Release Notes

The free plugin is prepared for WordPress.org distribution from the `woocommerce-sk-cz-functions/` directory.

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

- `woocommerce-sk-cz-functions/`

Typical local usage is to symlink or copy that directory into a WordPress site's `wp-content/plugins/` directory, then activate it with WooCommerce enabled.

