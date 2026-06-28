# Nimble.help - SK/CZ Store Tools for WooCommerce

This repository is the project root for the Nimble.help - SK/CZ Store Tools for WooCommerce product line.

Public GitHub repository:

- https://github.com/Ljuk67/Woo-sk-cz-functions

This repo is not a WordPress installation root. It is the project workspace that holds plugin code, shared docs, and release-related files.

## What This Project Is

Nimble.help - SK/CZ Store Tools for WooCommerce is a WordPress/WooCommerce plugin project focused on practical store features commonly needed in (but not only) Slovak and Czech ecommerce sites.

The free plugin currently includes these functions:

- company checkout toggle with company name, ICO, DIC, and IC DPH fields
- configurable checkout order button text with English default and Slovak/Czech translations
- GDPR consent checkbox for classic checkout, with privacy-policy link support
- fixed Cash on Delivery fee for the WooCommerce `cod` payment gateway
- hide paid shipping methods when free shipping is available
- hide the Additional Information product tab on single product pages
- optional move of Additional Information tab content into the long product description
- product subcategories row above products on category archive pages

Checkout-facing functions are implemented for both classic checkout and Checkout Block where the current free plugin supports that surface. The GDPR checkbox is intentionally limited to classic checkout because WooCommerce handles legal consent differently in Checkout Block.

The checkout button text setting appears in `WooCommerce > Settings > Nimble Woo SK/CZ` above the feature checkboxes. If the merchant leaves the predefined text unchanged or clears the field, the plugin uses the translated default for the active site language: English `Place order with payment obligation`, Slovak `Objednať s povinnosťou platby`, or Czech `Objednat s povinností platby`. Saving different text stores that merchant-defined wording for both classic checkout and Checkout Block.

## Repository Layout

```text
/ 
  AGENTS.md
  README.md
  docs/
  memory/
  .local/                        # ignored; private machine-specific notes
  nimble-help-sk-cz-store-tools/    # free plugin root
  nimble-help-sk-cz-store-tools-premium/  # planned premium plugin root
```


